<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Doctrine\ORM\EntityManager;
use Marcoshoya\MarquejogoBundle\Component\Person\PersonInterface;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;

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
     * @return \Marcoshoya\MarquejogoBundle\Entity\Provider
     */
    public function getUser()
    {
        $provider = $this->em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->findOneBy(array(
            'username' => $this->user->getUsername(),
            'password' => $this->user->getPassword(),
        ));

        if (!$provider instanceof \Marcoshoya\MarquejogoBundle\Entity\Provider) {

            return null;
        }

        return $provider;
    }

}
