<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Schedule entity
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 *
 * @ORM\Table(name="schedule")
 * @ORM\Entity
 */
class Schedule
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ProviderProduct")
     * @ORM\JoinColumn(name="provider_product_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $providerProduct;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $date;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    /**
     * @ORM\Column(type="integer")
     */
    private $alocated;

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
     * Set date
     *
     * @param \DateTime $date
     * @return Schedule
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Schedule
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set available
     *
     * @param integer $available
     * @return Schedule
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get available
     *
     * @return integer
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Set alocated
     *
     * @param integer $alocated
     * @return Schedule
     */
    public function setAlocated($alocated)
    {
        $this->alocated = $alocated;

        return $this;
    }

    /**
     * Get alocated
     *
     * @return integer
     */
    public function getAlocated()
    {
        return $this->alocated;
    }

    /**
     * Set providerProduct
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\ProviderProduct $providerProduct
     * @return Schedule
     */
    public function setProviderProduct(\Marcoshoya\MarquejogoBundle\Entity\ProviderProduct $providerProduct = null)
    {
        $this->providerProduct = $providerProduct;

        return $this;
    }

    /**
     * Get providerProduct
     *
     * @return \Marcoshoya\MarquejogoBundle\Entity\ProviderProduct
     */
    public function getProviderProduct()
    {
        return $this->providerProduct;
    }
}
