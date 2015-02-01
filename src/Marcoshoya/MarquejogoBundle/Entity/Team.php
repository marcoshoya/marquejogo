<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Team entity
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 * 
 * @ORM\Table(name="team")
 * @ORM\Entity
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Campo obrigatório", groups={"register"})
     */
    private $name;
    
    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="team")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * 
     * @return string
     */
    public function __toString()
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
     * @return Team
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
     * Set owner
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Customer $owner
     * @return Team
     */
    public function setOwner(\Marcoshoya\MarquejogoBundle\Entity\Customer $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Marcoshoya\MarquejogoBundle\Entity\Customer 
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
