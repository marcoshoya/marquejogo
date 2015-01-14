<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AdmUser entity class
 * 
 * @author  Marcos Joia <marcoshoya at gmail dot com>
 * @package Marcoshoya\MarquejogoBundle\Entity
 * 
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class AdmUser implements UserInterface {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message = "Campo obrigatório")
     */
    private $username;
   
    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(message = "Campo obrigatório")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;
    
    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;
        
    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->isActive = true;
    }
    
    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_ADMIN');
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
     * Set username
     *
     * @param string $username
     * @return AdmUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return AdmUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return AdmUser
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return AdmUser
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
