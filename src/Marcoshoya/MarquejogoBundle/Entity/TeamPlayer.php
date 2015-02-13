<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TeamPlayer entity
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 *
 * @ORM\Table(name="team_player")
 * @ORM\Entity
 */
class TeamPlayer
{

    /**
     * @ORM\Id
     * @ORM\Column(name="team_id", type="integer")
     */
    private $teamId;

    /**
     * @ORM\Id
     * @ORM\Column(name="customer_id", type="integer")
     */
    private $playerId;

    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $player;

    /**
     * Constructor
     *
     * @param integer $team
     * @param integer $player
     */
    public function __construct($team, $player)
    {
        $this->teamId = $team;
        $this->playerId = $player;
    }

    /**
     * Set teamId
     *
     * @param integer $teamId
     * @return TeamPlayer
     */
    public function setTeamId($teamId)
    {
        $this->teamId = $teamId;

        return $this;
    }

    /**
     * Get teamId
     *
     * @return integer
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * Set playerId
     *
     * @param integer $playerId
     * @return TeamPlayer
     */
    public function setPlayerId($playerId)
    {
        $this->playerId = $playerId;

        return $this;
    }

    /**
     * Get playerId
     *
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return TeamPlayer
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
     * Set team
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Team $team
     * @return TeamPlayer
     */
    public function setTeam(\Marcoshoya\MarquejogoBundle\Entity\Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return \Marcoshoya\MarquejogoBundle\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set player
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Customer $player
     * @return TeamPlayer
     */
    public function setPlayer(\Marcoshoya\MarquejogoBundle\Entity\Customer $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \Marcoshoya\MarquejogoBundle\Entity\Customer
     */
    public function getPlayer()
    {
        return $this->player;
    }

}
