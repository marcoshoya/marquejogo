<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Doctrine\ORM\EntityManager;
use Marcoshoya\MarquejogoBundle\Component\Person\PersonInterface;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;

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
     * @var Doctrine\ORM\EntityManager;
     */
    private $user;

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
     * Set user
     * 
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     * 
     * @return \Marcoshoya\MarquejogoBundle\Entity\Customer
     */
    public function getUser()
    {
        $customer = $this->em->getRepository('MarcoshoyaMarquejogoBundle:Customer')->findOneBy(array(
            'username' => $this->user->getUsername(),
            'password' => $this->user->getPassword(),
        ));

        if (!$customer instanceof \Marcoshoya\MarquejogoBundle\Entity\Customer) {

            return null;
        }

        return $customer;
    }

}
