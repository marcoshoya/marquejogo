<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Doctrine\ORM\EntityManager;
use Marcoshoya\MarquejogoBundle\Component\Person\PersonInterface;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;

/**
 * Description of AdmUserDelegate
 *
 * @author Marcos
 */
class AdmUserDelegate implements PersonInterface
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
     * @return \Marcoshoya\MarquejogoBundle\Entity\AdmUser
     */
    public function getUser()
    {
        $admuser = $this->em->getRepository('MarcoshoyaMarquejogoBundle:AdmUser')->findOneBy(array(
            'username' => $this->user->getUsername(),
            'password' => $this->user->getPassword(),
        ));

        if (!$admuser instanceof \Marcoshoya\MarquejogoBundle\Entity\AdmUser) {

            return null;
        }

        return $admuser;
    }

}
