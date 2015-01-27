<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Doctrine\ORM\EntityManager;
use Marcoshoya\MarquejogoBundle\Component\Person\PersonInterface;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;

/**
 * BusinessProvider
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BusinessProvider implements PersonInterface
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
     * @return \Marcoshoya\MarquejogoBundle\Entity\Provider
     */
    public function getUser(UserInterface $user)
    {
        $provider = $this->em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->findOneBy(array(
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
        ));

        if (!$provider instanceof \Marcoshoya\MarquejogoBundle\Entity\Provider) {

            return null;
        }

        return $provider;
    }

}
