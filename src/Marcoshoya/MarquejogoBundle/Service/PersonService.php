<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Doctrine\ORM\EntityManager;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;
use Marcoshoya\MarquejogoBundle\Component\Person\CustomerDelegate;
use Marcoshoya\MarquejogoBundle\Component\Person\ProviderDelegate;
use Marcoshoya\MarquejogoBundle\Component\Person\AdmUserDelegate;

/**
 * PersonService delegates who is called to get user
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class PersonService
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
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
    
    /**
     * Get user
     * 
     * @param UserInterface $user
     * 
     * @return Customer|Provider|AdmUser
     */
    public function getUser(UserInterface $user)
    {
        if ($user instanceof \Marcoshoya\MarquejogoBundle\Entity\Customer) {
            $customer = new CustomerDelegate($this->em);
            $customer->setUser($user);
            
            return $customer->getUser();
        }
        
        if ($user instanceof \Marcoshoya\MarquejogoBundle\Entity\Provider) {
            $provider = new ProviderDelegate($this->em);
            $provider->setUser($user);
            
            return $provider->getUser();
        }
        
        if ($user instanceof \Marcoshoya\MarquejogoBundle\Entity\AdmUser) {
            $admuser = new AdmUserDelegate($this->em);
            $admuser->setUser($user);
            
            return $admuser->getUser();
        }
        
        return null;
    }
}