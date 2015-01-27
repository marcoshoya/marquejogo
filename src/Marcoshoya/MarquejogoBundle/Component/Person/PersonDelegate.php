<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
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
    protected $em;
    
    protected $logger;


    /**
     * Constructor
     * 
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }
    
    public function getBusinessService(UserInterface $user)
    {
        if ($user instanceof \Marcoshoya\MarquejogoBundle\Entity\Customer) {
            
            return new BusinessCustomer($this->em);
        }
        
        if ($user instanceof \Marcoshoya\MarquejogoBundle\Entity\Provider) {
            
            return new BusinessProvider($this->em, $this->logger);
        }
        
        if ($user instanceof \Marcoshoya\MarquejogoBundle\Entity\AdmUser) {
            return new BusinessAdm($this->em);
        }
    }
    
    
}
