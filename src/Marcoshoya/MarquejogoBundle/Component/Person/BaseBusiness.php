<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * BaseBusiness
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BaseBusiness
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Symfony\Bridge\Monolog\Logger
     */
    protected $logger;
    
    /**
     * @var Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;
    
    /**
     * @var Symfony\Component\Security\Core\SecurityContext
     */
    protected $security;
    
    /**
     * BaseBusiness constructor
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
    }
}
