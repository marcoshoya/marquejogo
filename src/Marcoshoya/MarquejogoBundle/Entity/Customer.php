<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Customer entity class
 * 
 * @author  Marcos Joia <marcoshoya at gmail dot com>
 * 
 * @ORM\Table(name="customer")
 * @ORM\Entity
 * @UniqueEntity(
 *      fields="email", 
 *      groups="unique",
 *      message="Email já cadastrado"
 * )
 */
class Customer
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     * @Assert\NotBlank(message="Campo obrigatório", groups={"unique", "login"})
     * @Assert\Email(message="Formato do email inválido", groups={"unique", "login"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=40, nullable=false)
     * @Assert\NotBlank(message="Campo obrigatório", groups={"login"})
     */
    private $password;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $cpf;

    /**
     * @ORM\Column(type="string", nullable=false, columnDefinition="ENUM('male', 'female')")
     */
    private $gender;
    
    /**
     * @ORM\Column(type="string", nullable=false, columnDefinition="ENUM('goalkeeper', 'defender', 'middle', 'attacker')")
     */
    private $position;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $phone;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;
    
    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $number;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $complement;
    
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $neighborhood;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $city;
    
    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $state;
    
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
        return array('ROLE_CUSTOMER');
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * Set cpf
     *
     * @param string $cpf
     * @return Customer
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get cpf
     *
     * @return string 
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return Customer
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return Customer
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return Customer
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
}
