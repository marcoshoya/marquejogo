<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Doctrine\ORM\EntityManager;
use Marcoshoya\MarquejogoBundle\Component\Person\PersonInterface;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;

/**
 * BusinessCustomer
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BusinessCustomer implements PersonInterface
{

    /**
     * @var Doctrine\ORM\EntityManager;
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
     * @return \Marcoshoya\MarquejogoBundle\Entity\Customer
     */
    public function getUser(UserInterface $user)
    {
        $customer = $this->em->getRepository('MarcoshoyaMarquejogoBundle:Customer')->findOneBy(array(
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
        ));

        if (!$customer instanceof \Marcoshoya\MarquejogoBundle\Entity\Customer) {

            return null;
        }

        return $customer;
    }

}
