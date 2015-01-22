<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Marcoshoya\MarquejogoBundle\Entity\Autocomplete;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Helper\BundleHelper;

/**
 * AutocompleteService implements the observer
 *
 * @author marcos
 */
class AutocompleteService implements \SplObserver
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
     * @inheritDoc
     */
    public function update(\SplSubject $entity)
    {
        try {
            
            // prepare fields
            $city = $entity->getCity();
            $state = $city->getState();
            $cityName = ucwords($city->getName());
            $stateName = ucwords($state->getName());
            $nameField = sprintf('%s, %s', $cityName, $stateName);
            $nameUrl = BundleHelper::sluggable(sprintf('%s %s', $cityName, $stateName));
            
            $autocomplete = $this->getAutocomplete($entity);
            if (!$autocomplete) {
                $autocomplete = new Autocomplete();
            }
            
            $autocomplete->setCity($city);
            $autocomplete->setCityName($cityName);
            $autocomplete->setStateName($stateName);
            $autocomplete->setNameField($nameField);
            $autocomplete->setNameUrl($nameUrl);
            
            $this->em->persist($autocomplete);
            $this->em->flush();
            
        } catch (\Exception $ex) {
            $this->logger->error("AutocompleteService error: " . $ex->getMessage());
        }
    }
    
    /**
     * Get entity if already exists
     * 
     * @param Provider $entity
     * @return Autocomplete
     */
    private function getAutocomplete(Provider $entity) 
    {
        return $this->em->getRepository('MarcoshoyaMarquejogoBundle:Autocomplete')->findOneBy(array(
           'city' => $entity->getCity()
        ));
    }
}
