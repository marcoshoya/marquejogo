<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Entity\Autocomplete;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Entity\LocationCity;
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
            $city = $entity->getCity();
            if ($city instanceof LocationCity) {

                $values = $this->formatAutocomplete($city);
                $autocomplete = $this->getAutocomplete($entity);

                $autocomplete->setCity($city);
                $autocomplete->setCityName($values['cityName']);
                $autocomplete->setStateName($values['stateName']);
                $autocomplete->setNameField($values['nameField']);
                $autocomplete->setNameUrl($values['nameUrl']);

                $this->getEm()->persist($autocomplete);
                $this->getEm()->flush();

                $this->updateList();
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

    /**
     * Removing invalid entries from autocomplete
     * 
     * @return boolean
     */
    private function updateList()
    {
        try {

            $subq = $this->getEm()->createQueryBuilder();
            $subq->select("IDENTITY(p.city)")
                ->from("MarcoshoyaMarquejogoBundle:Provider", "p");

            $qb = $this->getEm()->createQueryBuilder();
            $query = $qb
                ->delete()
                ->from("MarcoshoyaMarquejogoBundle:Autocomplete", "ac")
                ->where($qb->expr()->notIn("ac.city", $subq->getDQL()))
                ->getQuery();

            return $query->getResult();
        } catch (\Exception $ex) {
            $this->getLogger()->error("AutocompleteService error: " . $ex->getMessage());
        }
    }

    /**
     * Format the fields
     * 
     * @param LocationCity $city
     * @return array
     */
    private function formatAutocomplete(LocationCity $city)
    {
        $state = $city->getState();
        $cityName = ucwords($city->getName());
        $stateName = ucwords($state->getName());

        return array(
            'cityName' => $cityName,
            'stateName' => $stateName,
            'nameField' => sprintf('%s, %s', $cityName, $stateName),
            'nameUrl' => BundleHelper::sluggable(sprintf('%s %s', $cityName, $stateName)),
        );
    }

}
