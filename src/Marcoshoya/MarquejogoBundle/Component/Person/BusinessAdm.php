<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Marcoshoya\MarquejogoBundle\Component\Person\BaseBusiness;
use Marcoshoya\MarquejogoBundle\Component\Person\PersonInterface;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * BusinessAdm
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BusinessAdm extends BaseBusiness implements PersonInterface
{
    
    /**
     * @var string
     */
    const providerKey = 'admin';
    
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
    
    /**
     * @inheritDoc
     */
    public function doAuth(UserInterface $user)
    {
        try {
            
            $token = new UsernamePasswordToken($user, null, self::providerKey, $user->getRoles());

            $this->security->setToken($token);
            $this->session->set('_security_main', serialize($token));

            if (!$this->security->isGranted('ROLE_ADMIN')) {
                throw new AccessDeniedHttpException();
            }

        } catch (AccessDeniedHttpException $ex) {
            $this->security->setToken(null);
            $this->logger->error('BusinessAdm error: ' . $ex->getMessage());
        }
    }

}
