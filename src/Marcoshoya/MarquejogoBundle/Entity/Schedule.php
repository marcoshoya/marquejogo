<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Schedule entity
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 *
 * @ORM\Table(name="schedule")
 * @ORM\Entity
 */
class Schedule
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Provider", inversedBy="schedule")
     * @ORM\JoinColumn(name="provider_id", referencedColumnName="id")
     */
    private $provider;

    /**
     * @ORM\OneToMany(targetEntity="Schedule", mappedBy="schedule")
     */
    private $scheduleItem;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->scheduleItem = new ArrayCollection();
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
     * Set provider
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Provider $provider
     * @return Schedule
     */
    public function setProvider(\Marcoshoya\MarquejogoBundle\Entity\Provider $provider = null)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return \Marcoshoya\MarquejogoBundle\Entity\Provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Add scheduleItem
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Schedule $scheduleItem
     * @return Schedule
     */
    public function addScheduleItem(\Marcoshoya\MarquejogoBundle\Entity\Schedule $scheduleItem)
    {
        $this->scheduleItem[] = $scheduleItem;

        return $this;
    }

    /**
     * Remove scheduleItem
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Schedule $scheduleItem
     */
    public function removeScheduleItem(\Marcoshoya\MarquejogoBundle\Entity\Schedule $scheduleItem)
    {
        $this->scheduleItem->removeElement($scheduleItem);
    }

    /**
     * Get scheduleItem
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScheduleItem()
    {
        return $this->scheduleItem;
    }
}
