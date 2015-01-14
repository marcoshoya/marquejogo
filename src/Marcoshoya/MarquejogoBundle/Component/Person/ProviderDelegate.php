<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Marcoshoya\MarquejogoBundle\Component\Person\PersonInterface;
use Doctrine\ORM\EntityManager;

/**
 * ProviderDelegate
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ProviderDelegate implements PersonInterface
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