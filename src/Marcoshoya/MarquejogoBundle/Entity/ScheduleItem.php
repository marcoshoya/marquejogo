<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ScheduleItem entity
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 *
 * @ORM\Table(name="schedule_item")
 * @ORM\Entity
 */
class ScheduleItem
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ProviderProduct", inversedBy="scheduleItem")
     * @ORM\JoinColumn(name="provider_product_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $providerProduct;

    /**
     * @ORM\ManyToOne(targetEntity="Schedule", inversedBy="scheduleItem")
     * @ORM\JoinColumn(name="schedule_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $schedule;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $date;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    /**
     * @ORM\Column(type="integer")
     */
    private $alocated;
    
    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->providerProduct = new ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     * @return ScheduleItem
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return ScheduleItem
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
     * Set available
     *
     * @param boolean $available
     * @return ScheduleItem
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get available
     *
     * @return boolean
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Set alocated
     *
     * @param integer $alocated
     * @return ScheduleItem
     */
    public function setAlocated($alocated)
    {
        $this->alocated = $alocated;

        return $this;
    }

    /**
     * Get alocated
     *
     * @return integer
     */
    public function getAlocated()
    {
        return $this->alocated;
    }

    /**
     * Set providerProduct
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\ProviderProduct $providerProduct
     * @return ScheduleItem
     */
    public function setProviderProduct(\Marcoshoya\MarquejogoBundle\Entity\ProviderProduct $providerProduct = null)
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

    /**
     * Set schedule
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Schedule $schedule
     * @return ScheduleItem
     */
    public function setSchedule(\Marcoshoya\MarquejogoBundle\Entity\Schedule $schedule = null)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get schedule
     *
     * @return \Marcoshoya\MarquejogoBundle\Entity\Schedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

}
