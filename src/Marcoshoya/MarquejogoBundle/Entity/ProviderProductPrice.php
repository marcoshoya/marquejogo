<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProviderProductPrice entity class
 *
 * @author  Marcos Joia <marcoshoya at gmail dot com>
 *
 * @ORM\Table(name="provider_product_price")
 * @ORM\Entity
 */
class ProviderProductPrice
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ProviderProduct")
     * @ORM\JoinColumn(name="provider_product_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $providerProduct;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $time;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=false)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $price;
    
    

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
     * Set time
     *
     * @param string $time
     * @return ProviderProductPrice
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return string 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return ProviderProductPrice
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
     * Set providerProduct
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\ProviderProduct $providerProduct
     * @return ProviderProductPrice
     */
    public function setProviderProduct(\Marcoshoya\MarquejogoBundle\Entity\ProviderProduct $providerProduct)
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
