<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Session\Session;
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
     * @var Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;
    
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
    public function __construct(EntityManager $em, Logger $logger, Session $session)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->session = $session;
        $this->personDelegate = new PersonDelegate($em, $logger); 
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
    
    /**
     * Get session
     *
     * @return Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession()
    {
        return $this->session;
    }
}
