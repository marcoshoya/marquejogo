<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;
use Marcoshoya\MarquejogoBundle\Helper\BundleHelper;

/**
 * Customer entity class
 *
 * @author  Marcos Joia <marcoshoya at gmail dot com>
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity
 * @UniqueEntity(
 *      fields="username",
 *      groups={"unique", "register", "book"},
 *      message="Email já cadastrado"
 * )
 */
class Customer implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="email", type="string", length=150, nullable=false)
     * @Assert\NotBlank(message="Campo obrigatório", groups={"unique", "login", "register", "book", "edit"})
     * @Assert\Email(message="Formato do email inválido", groups={"unique", "login", "register", "book", "edit"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=40, nullable=false)
     * @Assert\NotBlank(message="Campo obrigatório", groups={"login", "register", "book", "edit"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Campo obrigatório", groups={"register", "book", "edit"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Campo obrigatório", groups={"register", "edit"})
     */
    private $cpf;

    /**
     * @ORM\Column(type="string", nullable=false, columnDefinition="ENUM('male', 'female')")
     * @Assert\NotBlank(message="Campo obrigatório", groups={"edit"})
     */
    private $gender;

    /**
     * @ORM\Column(type="string", nullable=false, columnDefinition="ENUM('goalkeeper', 'defender', 'middle', 'attacker')")
     * @Assert\NotBlank(message="Campo obrigatório", groups={"edit"})
     */
    private $position;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\NotBlank(message="Campo obrigatório", groups={"edit"})
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Campo obrigatório", groups={"register", "book", "edit"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Campo obrigatório", groups={"edit"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Assert\NotBlank(message="Campo obrigatório", groups={"edit"})
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $complement;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\NotBlank(message="Campo obrigatório", groups={"edit"})
     */
    private $neighborhood;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotBlank(message="Campo obrigatório", groups={"edit"})
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="LocationState")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Campo obrigatório", groups={"edit"})
     */
    private $state;
    
    /**
     * @ORM\OneToMany(targetEntity="Team", mappedBy="owner", cascade={"persist"}) 
     */
    private $team;
    
    /**
     * @ORM\OneToMany(targetEntity="Book", mappedBy="customer") 
     * */
    private $book;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->team = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Gets position name
     * 
     * @return type
     */
    public function getPositionName()
    {
        return BundleHelper::positionName($this->getPosition());
    }
    
    /**
     * Gets locate formated
     * 
     * @return string
     */
    public function getLocate()
    {
        if ($this->getState() instanceof \Marcoshoya\MarquejogoBundle\Entity\LocationState) {
            
            return sprintf('%s/%s', ucwords($this->getCity()), ucwords($this->getState()->getName()));
        } else {
            
            return 'n/a';
        }
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
     * @param \Marcoshoya\MarquejogoBundle\Entity\LocationState $state
     * @return Customer
     */
    public function setState(\Marcoshoya\MarquejogoBundle\Entity\LocationState $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \Marcoshoya\MarquejogoBundle\Entity\LocationState 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Add team
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Team $team
     * @return Customer
     */
    public function addTeam(\Marcoshoya\MarquejogoBundle\Entity\Team $team)
    {
        $this->team[] = $team;

        return $this;
    }

    /**
     * Remove team
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Team $team
     */
    public function removeTeam(\Marcoshoya\MarquejogoBundle\Entity\Team $team)
    {
        $this->team->removeElement($team);
    }

    /**
     * Get team
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Add book
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Book $book
     * @return Customer
     */
    public function addBook(\Marcoshoya\MarquejogoBundle\Entity\Book $book)
    {
        $this->book[] = $book;

        return $this;
    }

    /**
     * Remove book
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Book $book
     */
    public function removeBook(\Marcoshoya\MarquejogoBundle\Entity\Book $book)
    {
        $this->book->removeElement($book);
    }

    /**
     * Get book
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBook()
    {
        return $this->book;
    }
}
