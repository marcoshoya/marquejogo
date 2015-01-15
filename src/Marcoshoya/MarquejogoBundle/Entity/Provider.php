<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;

/**
 * Provider entity class
 * 
 * @author  Marcos Joia <marcoshoya at gmail dot com>
 * @package Marcoshoya\MarquejogoBundle\Entity
 * 
 * @ORM\Table(name="provider")
 * @ORM\Entity(repositoryClass="Marcoshoya\MarquejogoBundle\Repository\ProviderRepository")
 */
class Provider implements UserInterface
{    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(name="email", type="string", length=60)
     * @Assert\NotBlank(message="Campo obrigatório")
     * @Assert\Email(message="Formato do email inválido")
     */
    private $username;
    
    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $password;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;
    
    /**
     * @ORM\Column(type="text")
     */
    private $description;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $cnpj;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $phone;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;
    
    /**
     * @ORM\Column(type="string", length=25)
     */
    private $number;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $complement;
    
    /**
     * @ORM\Column(type="string", length=150)
     */
    private $neighborhood;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $city;
    
    /**
     * @ORM\Column(type="string", length=2)
     */
    private $state;
    
    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;
    
    /** 
     * @ORM\OneToMany(targetEntity="ProviderProduct", mappedBy="provider") 
     **/
    private $providerProduct;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->providerProduct = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * To string class
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
    
    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_PROVIDER');
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Provider
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
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
     * Set email
     *
     * @param string $email
     * @return Provider
     */
    public function setUsername($email)
    {
        $this->username = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Provider
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Provider
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
     * Set cnpj
     *
     * @param string $cnpj
     * @return Provider
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    /**
     * Get cnpj
     *
     * @return string 
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Provider
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Provider
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return Provider
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set complement
     *
     * @param string $complement
     * @return Provider
     */
    public function setComplement($complement)
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * Get complement
     *
     * @return string 
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * Set neighborhood
     *
     * @param string $neighborhood
     * @return Provider
     */
    public function setNeighborhood($neighborhood)
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    /**
     * Get neighborhood
     *
     * @return string 
     */
    public function getNeighborhood()
    {
        return $this->neighborhood;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Provider
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Provider
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Provider
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
     * Set description
     *
     * @param string $description
     * @return Provider
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add providerProduct
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\ProviderProduct $providerProduct
     * @return Provider
     */
    public function addProviderProduct(\Marcoshoya\MarquejogoBundle\Entity\ProviderProduct $providerProduct)
    {
        $this->providerProduct[] = $providerProduct;

        return $this;
    }

    /**
     * Remove providerProduct
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\ProviderProduct $providerProduct
     */
    public function removeProviderProduct(\Marcoshoya\MarquejogoBundle\Entity\ProviderProduct $providerProduct)
    {
        $this->providerProduct->removeElement($providerProduct);
    }

    /**
     * Get providerProduct
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProviderProduct()
    {
        return $this->providerProduct;
    }
}
