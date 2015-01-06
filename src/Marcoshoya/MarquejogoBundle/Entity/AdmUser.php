<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
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
class AdmUser implements UserInterface, \Serializable {
    
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
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $salt;
    
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
     * @ORM\Column(name="user_type",type="string", columnDefinition="ENUM('adm', 'provider', 'customer')") 
     */
    private $userType;
    
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
        $this->salt = md5(uniqid(null, true));
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
    public function getSalt()
    {
        return $this->salt;
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
        if ($this->getUserType() === 'adm') {
            
            return array('ROLE_ADMIN');
        } if ($this->getUserType() === 'provider') {
            
            return array('ROLE_PROVIDER');
        } else {
            
            return array('ROLE_CUSTOMER');
        }
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
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
     * Set salt
     *
     * @param string $salt
     * @return AdmUser
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

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

    /**
     * Set userType
     *
     * @param string $userType
     * @return AdmUser
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get userType
     *
     * @return string 
     */
    public function getUserType()
    {
        return $this->userType;
    }
}
