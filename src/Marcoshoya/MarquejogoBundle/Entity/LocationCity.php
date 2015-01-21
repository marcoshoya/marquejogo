<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="location_city")
 * @ORM\Entity
 */
class LocationCity
{

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="LocationState", inversedBy="city")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id")
     * */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $name;

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
     * @return LocationCity
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
     * Set state
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\LocationState $state
     * @return LocationCity
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

}
