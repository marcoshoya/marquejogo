<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Component\Person\BaseBusiness;
use Marcoshoya\MarquejogoBundle\Component\Person\PersonInterface;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;
use Marcoshoya\MarquejogoBundle\Service\AutocompleteService;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * BusinessProvider
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BusinessProvider extends BaseBusiness implements PersonInterface
{
    /**
     * @var string
     */
    const providerKey = 'provider';
    
    /**
     * @var \Marcoshoya\MarquejogoBundle\Entity\Provider
     */
    private $provider;
    
    /**
     * Sets provider
     * 
     * @param Provider $provider
     */
    public function setProvider(Provider $provider)
    {
        $this->provider = $provider;
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

        if (!$provider instanceof Provider) {

            return null;
        }
        
        // sets provider object
        $this->setProvider($provider);

        return $provider;
    }
    
    /**
     * Do auth
     * 
     * @param Provider $entity
     * @return boolean
     * @throws AccessDeniedHttpException
     */
    public function doAuth(UserInterface $entity)
    {
        try {

            $token = new UsernamePasswordToken($entity, null, self::providerKey, $entity->getRoles());

            $this->security->setToken($token);
            $this->session->set('_security_main', serialize($token));

            if (!$this->security->isGranted(array('ROLE_PROVIDER'))) {
                throw new AccessDeniedHttpException();
            }
            
            return true;

        } catch (AccessDeniedHttpException $ex) {
            $this->security->setToken(null);
            $this->logger->error('BusinessProvider error: ' . $ex->getMessage());
        }
    }

    /**
     * Update entity
     *
     * @param Provider $provider
     */
    public function update(Provider $provider, AutocompleteService $autocomplete)
    {
        try {

            // persist subject object
            $this->em->persist($provider);
            $this->em->flush();

            // notify observers
            $provider->attach($autocomplete);
            $provider->notify();
            
        } catch (\Exception $e) {
            $this->logger->error("BusinessProvider error: " . $e->getMessage());
        }
    }
    
    /**
     * Get picture
     * @param Provider $provider
     * 
     * @return ProviderPicture
     */
    public function getPicture()
    {
        try {

            $picture = $this->em
                ->getRepository('MarcoshoyaMarquejogoBundle:ProviderPicture')
                ->findMainPicture($this->provider);

            return $picture;
        } catch (\Exception $ex) {
            $this->logger->error("BusinessProvider error: " . $ex->getMessage());
        }
    }
    
    /**
     * Get picture
     * @param Provider $provider
     * 
     * @return ProviderPicture
     */
    public function getAllPicture()
    {
        try {

            $picture = $this->em
                ->getRepository('MarcoshoyaMarquejogoBundle:ProviderPicture')
                ->findAllPicture($this->provider);

            return $picture;
        } catch (\Exception $ex) {
            $this->logger->error("BusinessProvider error: " . $ex->getMessage());
        }
    }

}
