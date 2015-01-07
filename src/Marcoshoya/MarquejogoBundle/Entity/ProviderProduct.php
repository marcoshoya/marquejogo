<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProviderProduct entity class
 *
 * @author  Marcos Joia <marcoshoya at gmail dot com>
 * @package Marcoshoya\MarquejogoBundle\Entity
 *
 * @ORM\Table(name="provider_product")
 * @ORM\Entity
 */
class ProviderProduct
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Provider", inversedBy="providerProduct")
     * @ORM\JoinColumn(name="provider_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $provider;

    /**
     * @ORM\Column(type="string", length=60, nullable=false)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=false, columnDefinition="ENUM('open', 'close')")
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $category;

    /**
     * @ORM\Column(type="string", nullable=false, columnDefinition="ENUM('soccer', 'voley', 'swiss')")
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $type;

    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * To string class
     *
     * @return string
     */
    public function toString()
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
     * Set name
     *
     * @param string $name
     * @return ProviderProduct
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
     * Set category
     *
     * @param string $category
     * @return ProviderProduct
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return ProviderProduct
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return ProviderProduct
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set provider
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Provider $provider
     * @return ProviderProduct
     */
    public function setProvider(\Marcoshoya\MarquejogoBundle\Entity\Provider $provider = null)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return \Marcoshoya\MarquejogoBundle\Entity\Provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

}
