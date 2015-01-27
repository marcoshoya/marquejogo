<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Doctrine\ORM\EntityManager;
use Marcoshoya\MarquejogoBundle\Component\Person\PersonDelegate;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;

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
    
    private $personDelegate;
    
    /**
     * Constructor
     * 
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->personDelegate = new PersonDelegate($this->em); 
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
        $service = $this->personDelegate->getBusinessService($user);
        
        return $service->getUser($user);
    }
}
