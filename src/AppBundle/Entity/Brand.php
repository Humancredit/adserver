<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Christian David
 * @ORM\Entity
 */
class Brand
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Banner", mappedBy="brand")
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
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     * @return Brand
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add banners
     *
     * @param \AppBundle\Entity\Banner $banners
     * @return Brand
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
