<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Provider entity class
 * 
 * @author  Marcos Joia <marcoshoya at gmail dot com>
 * @package Marcoshoya\MarquejogoBundle\Entity
 * 
 * @ORM\Table(name="provider")
 * @ORM\Entity(repositoryClass="Marcoshoya\MarquejogoBundle\Repository\ProviderRepository")
 */
class Provider 
{    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $password;
    
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $cnpj;
    
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $phone;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $address;
    
    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $number;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $complement;
    
    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $neighborhood;
    
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $city;
    
    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $state;
    
    /**
     * @ORM\Column(name="is_active", type="boolean")
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
     * Set email
     *
     * @param string $email
     * @return Provider
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
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
}