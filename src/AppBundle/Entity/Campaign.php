<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Christian David
 * @ORM\Entity
 */
class Campaign
{
    const NUM_ITEMS = 10;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @var string
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Banner", mappedBy="campaign")
     */
    private $banners;

    /**
     *
     */
    public function __construct()
    {
        $this->banners = new ArrayCollection();
    }

    /**
     *
     */
    public function __toString()
    {
        return $this->getTitle();
    }

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
     * @return Campaign
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
     * Set slug
     *
     * @param string $slug
     * @return Campaign
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add banners
     *
     * @param \AppBundle\Entity\Banner $banners
     * @return Campaign
     */
    public function addBanner(\AppBundle\Entity\Banner $banners)
    {
        $this->banners[] = $banners;

        return $this;
    }

    /**
     * Remove banners
     *
     * @param \AppBundle\Entity\Banner $banners
     */
    public function removeBanner(\AppBundle\Entity\Banner $banners)
    {
        $this->banners->removeElement($banners);
    }

    /**
     * Get banners
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBanners()
    {
        return $this->banners;
    }

}
