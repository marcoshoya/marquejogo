<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Entity\Autocomplete;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Helper\BundleHelper;

/**
 * AutocompleteService implements the observer
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class AutocompleteService extends BaseService implements \SplObserver
{

    /**
     * @inheritDoc
     */
    public function update(\SplSubject $entity)
    {
        try {

            // prepare fields
            $city = $entity->getCity();
            if ($city instanceof \Marcoshoya\MarquejogoBundle\Entity\LocationCity) {
                $state = $city->getState();
                $cityName = ucwords($city->getName());
                $stateName = ucwords($state->getName());
                $nameField = sprintf('%s, %s', $cityName, $stateName);
                $nameUrl = BundleHelper::sluggable(sprintf('%s %s', $cityName, $stateName));

                $autocomplete = $this->getAutocomplete($entity);
                $autocomplete->setCity($city);
                $autocomplete->setCityName($cityName);
                $autocomplete->setStateName($stateName);
                $autocomplete->setNameField($nameField);
                $autocomplete->setNameUrl($nameUrl);

                $this->getEm()->persist($autocomplete);
                $this->getEm()->flush();
            }
        } catch (\Exception $ex) {
            $this->getLogger()->error("AutocompleteService error: " . $ex->getMessage());
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
        $autocomplete = $this->getEm()
            ->getRepository('MarcoshoyaMarquejogoBundle:Autocomplete')
            ->findOneBy(array(
                'city' => $entity->getCity()
            ));

        if (!$autocomplete) {
            $autocomplete = new Autocomplete();
        }

        return $autocomplete;
    }

    /**
     * Find city by slug
     * 
     * @param string $slug
     * 
     * @return Marcoshoya\MarquejogoBundle\Entity\Autocomplete
     */
    public function getCity($slug)
    {
        try {

            $city = $this->getEm()
                ->getRepository('MarcoshoyaMarquejogoBundle:Autocomplete')
                ->findOneBy(array(
                    'nameUrl' => $slug
                ));

            return $city;

        } catch (\Exception $ex) {
            $this->getLogger()->error("AutocompleteService error: " . $ex->getMessage());
        }
    }

}
