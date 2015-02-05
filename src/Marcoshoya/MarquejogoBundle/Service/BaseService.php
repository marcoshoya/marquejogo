<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
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
     * @var Symfony\Component\Security\Core\SecurityContext
     */
    private $security;
    
    /**
     * @var PersonDelegate
     */
    private $personDelegate;

    /**
     * BaseService constructor
     * 
     * @param EntityManager $em
     * @param Logger $logger
     * @param Session $session
     * @param SecurityContext $security
     */
    public function __construct(
        EntityManager $em, 
        Logger $logger, 
        Session $session, 
        SecurityContext $security
    ) {

        $this->em = $em;
        $this->logger = $logger;
        $this->session = $session;
        $this->security = $security;
        $this->personDelegate = new PersonDelegate($em, $logger, $session, $security); 
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
    
    /**
     * Get session
     *
     * @return Symfony\Component\Security\Core\SecurityContext
     */
    public function getSecurity()
    {
        return $this->security;
    }
}
