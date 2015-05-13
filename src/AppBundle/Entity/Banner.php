<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * Banner
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Banner
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=4096, nullable=true)
     * @var string
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity="Campaign", inversedBy="banners")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id")
     * @var \AppBundle\Entity\Campaign
     */
    private $campaign;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="banners")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @var \AppBundle\Entity\Category
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="banners")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * @var \AppBundle\Entity\Brand
     */
    private $brand;

    /**
     * @ORM\OneToMany(targetEntity="BannerLog", mappedBy="banner")
     */
    private $logs;

    /**
     * @ORM\OneToMany(targetEntity="BannerClick", mappedBy="banner")
     */
    private $clicks;

    /**
     * @ORM\OneToMany(targetEntity="BannerFeedback", mappedBy="banner")
     */
    private $feedbacks;

    /**
     * @Assert\File(maxSize="6000000", mimeTypes={"image/*"})
     */
    private $file;

    /**
     * we need a reference to the old path to delete/update properly
     */
    private $oldPath;

    // ----- additional methods ------------------------------------------------
    /**
     *
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     *
     */
    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    /**
     *
     */
    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    /**
     *
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    /**
     *
     */
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/banner';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->oldPath = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->oldPath)) {
            // delete the old image
            @unlink($this->getUploadRootDir().'/'.$this->oldPath);
            // clear the temp image path
            $this->oldPath = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            @unlink($file);
        }
    }

    // ----- auto generated getter/setter --------------------------------------
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Banner
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     * @return Banner
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set brand
     *
     * @param \AppBundle\Entity\Brand $brand
     * @return Banner
     */
    public function setBrand(\AppBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \AppBundle\Entity\Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Banner
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->logs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->clicks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->feedbacks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Banner
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Add logs
     *
     * @param \AppBundle\Entity\BannerLog $logs
     * @return Banner
     */
    public function addLog(\AppBundle\Entity\BannerLog $logs)
    {
        $this->logs[] = $logs;

        return $this;
    }

    /**
     * Remove logs
     *
     * @param \AppBundle\Entity\BannerLog $logs
     */
    public function removeLog(\AppBundle\Entity\BannerLog $logs)
    {
        $this->logs->removeElement($logs);
    }

    /**
     * Get logs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * Add clicks
     *
     * @param \AppBundle\Entity\BannerClick $clicks
     * @return Banner
     */
    public function addClick(\AppBundle\Entity\BannerClick $clicks)
    {
        $this->clicks[] = $clicks;

        return $this;
    }

    /**
     * Remove clicks
     *
     * @param \AppBundle\Entity\BannerClick $clicks
     */
    public function removeClick(\AppBundle\Entity\BannerClick $clicks)
    {
        $this->clicks->removeElement($clicks);
    }

    /**
     * Get clicks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * Add feedbacks
     *
     * @param \AppBundle\Entity\BannerFeedback $feedbacks
     * @return Banner
     */
    public function addFeedback(\AppBundle\Entity\BannerFeedback $feedbacks)
    {
        $this->feedbacks[] = $feedbacks;

        return $this;
    }

    /**
     * Remove feedbacks
     *
     * @param \AppBundle\Entity\BannerFeedback $feedbacks
     */
    public function removeFeedback(\AppBundle\Entity\BannerFeedback $feedbacks)
    {
        $this->feedbacks->removeElement($feedbacks);
    }

    /**
     * Get feedbacks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFeedbacks()
    {
        return $this->feedbacks;
    }

    /**
     * Set campaign
     *
     * @param \AppBundle\Entity\Campaign $campaign
     * @return Banner
     */
    public function setCampaign(\AppBundle\Entity\Campaign $campaign = null)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return \AppBundle\Entity\Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

}
