<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Doctrine\ORM\EntityManager;
use Marcoshoya\MarquejogoBundle\Component\Person\PersonInterface;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;

/**
 * BusinessAdm
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BusinessAdm implements PersonInterface
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
     * @return \Marcoshoya\MarquejogoBundle\Entity\AdmUser
     */
    public function getUser(UserInterface $user)
    {
        $admuser = $this->em->getRepository('MarcoshoyaMarquejogoBundle:AdmUser')->findOneBy(array(
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
        ));

        if (!$admuser instanceof \Marcoshoya\MarquejogoBundle\Entity\AdmUser) {

            return null;
        }

        return $admuser;
    }

}
