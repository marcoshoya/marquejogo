<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Service\AutocompleteService;

/**
 * Description of AutocompleteService
 *
 * @author marcos
 */
class ProviderService
{

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Symfony\Bridge\Monolog\Logger
     */
    protected $logger;

    /**
     * @var Marcoshoya\MarquejogoBundle\Service\AutocompleteService
     */
    public $autocomplete;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }
    
    /**
     * Get autocomplete observer
     * 
     * @return AutocompleteService
     * 
     * @throws \InvalidArgumentException
     */
    public function getAutocomplete()
    {
        if ($this->autocomplete instanceof AutocompleteService) {
            return $this->autocomplete;
        } else {
            throw new \InvalidArgumentException("Object have to be instance of AutocompleteService");
        }
    }
    
    /**
     * Update entity
     * 
     * @param Provider $provider
     */
    public function update(Provider $provider)
    {
        $autocomplete = $this->getAutocomplete();

        try {

            // persist subject object
            $this->em->persist($provider);
            $this->em->flush();

            // notify observers
            $provider->attach($autocomplete);
            $provider->notify();
            
        } catch (\Exception $e) {
            $this->logger->error("ProviderService error: " . $e->getMessage());
        }
    }

}
