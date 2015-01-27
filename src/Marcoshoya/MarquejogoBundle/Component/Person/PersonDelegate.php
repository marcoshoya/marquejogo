<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Doctrine\ORM\EntityManager;
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
    
    /**
     * Constructor
     * 
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getBusinessService(UserInterface $user)
    {
        if ($user instanceof \Marcoshoya\MarquejogoBundle\Entity\Customer) {
            
            return new BusinessCustomer($this->em);
        }
        
        if ($user instanceof \Marcoshoya\MarquejogoBundle\Entity\Provider) {
            
            return new BusinessProvider($this->em);
        }
        
        if ($user instanceof \Marcoshoya\MarquejogoBundle\Entity\AdmUser) {
            return new BusinessAdm($this->em);
        }
    }
    
    
}
