<?php

namespace Acme\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 */
class Product
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Acme\Entity\Category
     */
    private $category;


    /**
     * Set name
     *
     * @param string $name
     * @return Product
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set category
     *
     * @param \Acme\Entity\Category $category
     * @return Product
     */
    public function setCategory(\Acme\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Acme\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
