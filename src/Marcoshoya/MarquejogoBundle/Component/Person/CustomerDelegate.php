<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Marcoshoya\MarquejogoBundle\Component\Person\PersonInterface;
use Doctrine\ORM\EntityManager;

/**
 * Description of CustomerDelegate
 *
 * @author Marcos
 */
class CustomerDelegate implements PersonInterface
{
    /**
     * @var Doctrine\ORM\EntityManager;
     */
    protected $em;
    
    /**
     * @inheritDoc
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getUser()
    {
        
    }

    public function setUser(UserInterface $user)
    {
        
    }

}
