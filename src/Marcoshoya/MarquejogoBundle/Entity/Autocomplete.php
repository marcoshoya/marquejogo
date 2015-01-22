<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Autocomplete entity class
 *
 * @author  Marcos Lazarin <marcoshoya at gmail dot com>
 *
 * @ORM\Table(name="autocomplete")
 * @ORM\Entity
 */
class Autocomplete
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="LocationCity")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     **/
    private $city;
    
    /**
     * @ORM\Column(name="name_city", type="string", length=150)
     */
    private $cityName;
    
    /**
     * @ORM\Column(name="name_state", type="string", length=50)
     */
    private $stateName;

    /**
     * @ORM\Column(name="name_field", type="string", length=255)
     */
    private $nameField;
    
    /**
     * @ORM\Column(name="name_url", type="string", length=255)
     */
    private $nameUrl;

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
     * Set cityName
     *
     * @param string $cityName
     * @return Autocomplete
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;

        return $this;
    }

    /**
     * Get cityName
     *
     * @return string 
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * Set stateName
     *
     * @param string $stateName
     * @return Autocomplete
     */
    public function setStateName($stateName)
    {
        $this->stateName = $stateName;

        return $this;
    }

    /**
     * Get stateName
     *
     * @return string 
     */
    public function getStateName()
    {
        return $this->stateName;
    }

    /**
     * Set nameField
     *
     * @param string $nameField
     * @return Autocomplete
     */
    public function setNameField($nameField)
    {
        $this->nameField = $nameField;

        return $this;
    }

    /**
     * Get nameField
     *
     * @return string 
     */
    public function getNameField()
    {
        return $this->nameField;
    }

    /**
     * Set nameUrl
     *
     * @param string $nameUrl
     * @return Autocomplete
     */
    public function setNameUrl($nameUrl)
    {
        $this->nameUrl = $nameUrl;

        return $this;
    }

    /**
     * Get nameUrl
     *
     * @return string 
     */
    public function getNameUrl()
    {
        return $this->nameUrl;
    }

    /**
     * Set city
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\LocationCity $city
     * @return Autocomplete
     */
    public function setCity(\Marcoshoya\MarquejogoBundle\Entity\LocationCity $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Marcoshoya\MarquejogoBundle\Entity\LocationCity 
     */
    public function getCity()
    {
        return $this->city;
    }
}
