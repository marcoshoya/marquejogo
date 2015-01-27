<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Marcoshoya\MarquejogoBundle\Component\Person\PersonDelegate;

/**
 * BaseService implements some base functions
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BaseService
{

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var Symfony\Bridge\Monolog\Logger
     */
    private $logger;
    
    /**
     * @var PersonDelegate
     */
    private $personDelegate;

    /**
     * Constructor
     *
     * @param EntityManager $em
     * @param Logger $logger
     */
    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->setEm($em);
        $this->logger = $logger;
        $this->personDelegate = new PersonDelegate($em, $logger); 
    }

    /**
     * Set em
     * 
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Get em
     * 
     * @return Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * Set logger
     * 
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get logger
     * 
     * @return Symfony\Bridge\Monolog\Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    
    public function getPersonDelegate()
    {
        return $this->personDelegate;
    }
}
