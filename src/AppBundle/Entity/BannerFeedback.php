<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * BannerFeedback
 * @ORM\Entity
 */
class BannerFeedback
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var string
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="Banner", inversedBy="feedbacks")
     * @ORM\JoinColumn(name="banner_id", referencedColumnName="id")
     * @var \AppBundle\Entity\Banner
     */
    private $banner;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $rating;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $message;

    // ----- additional methods ------------------------------------------------
    /**
     *
     */
    public function __construct()
    {
        $this->setCreated(new \DateTime());
    }

    /**
     *
     */
    public function __toString()
    {
        return $this->getBanner()->__toString();
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
     * Set created
     *
     * @param \DateTime $created
     * @return BannerFeedback
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set banner
     *
     * @param \AppBundle\Entity\Banner $banner
     * @return BannerFeedback
     */
    public function setBanner(\AppBundle\Entity\Banner $banner = null)
    {
        $this->banner = $banner;

        return $this;
    }

    /**
     * Get banner
     *
     * @return \AppBundle\Entity\Banner
     */
    public function getBanner()
    {
        return $this->banner;
    }


    /**
     * Set rating
     *
     * @param integer $rating
     * @return BannerFeedback
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return BannerFeedback
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }
}
