<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Doctrine\ORM\EntityManager;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Component\Person\PersonInterface;
use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;
use Marcoshoya\MarquejogoBundle\Service\AutocompleteService;

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
    
    protected $provider;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, $logger, $provider)
    {
        $this->em = $em;
        $this->logger = $logger;
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

        return $provider;
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
            $this->getLogger()->error("BusinessProvider error: " . $ex->getMessage());
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
            $this->getLogger()->error("BusinessProvider error: " . $ex->getMessage());
        }
    }

}
