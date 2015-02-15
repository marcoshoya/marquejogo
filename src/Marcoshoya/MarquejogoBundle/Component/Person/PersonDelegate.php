<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;
use Marcoshoya\MarquejogoBundle\Component\Person\BusinessCustomer;
use Marcoshoya\MarquejogoBundle\Component\Person\BusinessProvider;
use Marcoshoya\MarquejogoBundle\Component\Person\BusinessAdm;

/**
 * PersonDelegate
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class PersonDelegate
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
     * BaseService constructor
     * 
     * @param EntityManager $em
     * @param Logger $logger
     * @param Session $session
     * @param SecurityContext $security
     */
    public function __construct(
    EntityManager $em, Logger $logger, Session $session, SecurityContext $security
    )
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->session = $session;
        $this->security = $security;
    }

    public function getBusinessService(UserInterface $user)
    {
        if ($user instanceof \Marcoshoya\MarquejogoBundle\Entity\Customer) {

            return new BusinessCustomer($this->em, $this->logger, $this->session, $this->security);
        }

        if ($user instanceof \Marcoshoya\MarquejogoBundle\Entity\Provider) {

            $bp = new BusinessProvider($this->em, $this->logger, $this->session, $this->security);
            $bp->setProvider($user);
            
            return $bp;
        }

        if ($user instanceof \Marcoshoya\MarquejogoBundle\Entity\AdmUser) {
            return new BusinessAdm($this->em);
        }
    }

}
