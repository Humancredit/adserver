<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * BannerClick
 * @ORM\Entity
 */
class BannerClick
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
     * @ORM\ManyToOne(targetEntity="Banner", inversedBy="clicks")
     * @ORM\JoinColumn(name="banner_id", referencedColumnName="id")
     * @var \AppBundle\Entity\Banner
     */
    private $banner;

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
     * @return BannerClick
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
     * @return BannerClick
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

}
