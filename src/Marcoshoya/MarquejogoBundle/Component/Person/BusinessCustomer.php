<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Marcoshoya\MarquejogoBundle\Component\Person\BaseBusiness;
use Marcoshoya\MarquejogoBundle\Component\Person\PersonInterface;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * BusinessCustomer
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BusinessCustomer extends BaseBusiness implements PersonInterface
{
    const providerKey = 'customer';
    
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
    
    /**
     * Do the auth
     * 
     * @param Customer $entity
     * @return boolean
     * @throws AccessDeniedHttpException
     */
    public function doAuth(UserInterface $entity)
    {
        try {
            
            $token = new UsernamePasswordToken($entity, null, self::providerKey, $entity->getRoles());

            $this->security->setToken($token);
            $this->session->set('_security_main', serialize($token));

            if (!$this->security->isGranted('ROLE_CUSTOMER')) {
                throw new AccessDeniedHttpException();
            }
            
            return true;

        } catch (AccessDeniedHttpException $ex) {
            $this->security->setToken(null);
            $this->logger->error('BusinessCustomer error: ' . $ex->getMessage());
            
            return false;
        }
    }

}
