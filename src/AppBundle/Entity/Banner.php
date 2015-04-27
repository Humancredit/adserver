<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Banner
 * @ORM\Entity
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
    private $embedCode = ".";

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
     * Set embedCode
     *
     * @param string $embedCode
     * @return Banner
     */
    public function setEmbedCode($embedCode)
    {
        $this->embedCode = $embedCode;

        return $this;
    }

    /**
     * Get embedCode
     *
     * @return string
     */
    public function getEmbedCode()
    {
        return $this->embedCode;
    }

}
