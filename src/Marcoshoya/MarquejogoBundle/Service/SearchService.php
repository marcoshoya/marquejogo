<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Component\Search\SearchCollection;
use Marcoshoya\MarquejogoBundle\Component\Search\SearchDTO;
use Marcoshoya\MarquejogoBundle\Entity\LocationCity;
use Marcoshoya\MarquejogoBundle\Entity\Schedule;
use Marcoshoya\MarquejogoBundle\Helper\BundleHelper;

/**
 * SearchService improves all search funtions
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class SearchService extends BaseService
{

    /**
     * @var Marcoshoya\MarquejogoBundle\Service\AutocompleteService
     */
    public $autocomplete;

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
     * Set session
     *
     * @param array $data
     */
    public function setSearchSession($data = array())
    {
        $slug = BundleHelper::sluggable($data['city']);
        $dateTime = $data['date'];
        $hour = $data['hour'];

        $dateTime->modify("+{$hour} hour");
        $autocomplete = $this->getAutocomplete()->getCity($slug);

        // dto
        $search = new SearchDTO();
        $search->setDate($dateTime);
        $search->setAutocomplete($autocomplete);
        $search->setSlug($slug);

        if ($this->getSession()->has(SearchDTO::session)) {
            $this->getSession()->remove(SearchDTO::session);
        }

        $this->getSession()->set(SearchDTO::session, serialize($search));
        
        return $search;
    }

    /**
     * Get search session
     *
     * @return SearchDTO|null
     */
    public function getSearchSession()
    {
        if ($this->getSession()->has(SearchDTO::session)) {
            $object = $this->getSession()->get(SearchDTO::session);

            return unserialize($object);
        }

        return null;
    }

    /**
     * Get standard search data in array
     *
     * @return array
     */
    public function getSearchData()
    {
        $searchDTO = $this->getSearchSession();
        if (null === $searchDTO) {
            $searchDTO = new SearchDTO();
        }

        return array(
            'city' => null !== $searchDTO->getAutocomplete() ? $searchDTO->getAutocomplete()->getNameField() : null,
            'date' => null !== $searchDTO->getDate() ? $searchDTO->getDate() : null,
            'hour' => null !== $searchDTO->getDate() ? $searchDTO->getDate()->format('H') : date('H'),
        );
    }

    /**
     * Do the search
     *
     * @param SearchDTO $search
     *
     * @return array an array of Provider
     */
    public function doSearch(SearchDTO $search)
    {
        $collection = new SearchCollection();

        if (null === $search->getAutocomplete()) {

            return $collection;
        }

        $city = $search->getAutocomplete()->getCity();
        $results = $this->getProviderByCity($city);

        if ($results) {
            foreach ($results as $provider) {
                $idx = $provider->getId();
                $collection->add($provider, $idx);
                // picture
                $picture = $this->getPersonDelegate()->getBusinessService($provider)->getPicture();
                if (null !== $picture) {
                    $collection->addPicture($picture, $idx);
                }
            }
        }

        return $collection;
    }

    /**
     * Get providers
     *
     * @param LocationCity $city
     * @return array an array of Provider
     */
    private function getProviderByCity(LocationCity $city)
    {
        try {

            $providers = $this->getEm()
                ->getRepository('MarcoshoyaMarquejogoBundle:Provider')
                ->findBy(array('city' => $city));

            return $providers;
        } catch (\Exception $ex) {
            $this->getLogger()->error("SearchService error: " . $ex->getMessage());
        }
    }

    /**
     * Gets all products available to sell by search criteria
     *
     * @param Schedule $schedule
     * @param SearchDTO $search
     *
     * @return array
     */
    public function getallProductBySearch(Schedule $schedule, SearchDTO $search)
    {
        try {

            $qb = $this->getEm()->createQueryBuilder()
                ->select('s')
                ->from('MarcoshoyaMarquejogoBundle:ScheduleItem', 's')
                ->where('s.schedule = :schedule')
                ->andWhere('s.date = :date')
                ->andWhere('s.alocated = :alocated')
                ->setParameters(array(
                    'schedule' => $schedule,
                    'date' => $search->getDate(),
                    'alocated' => 0,
                ));

            $query = $qb->getQuery();

            return $query->execute();
        } catch (\Exception $ex) {
            $this->getLogger()->error("SearchService error: " . $ex->getMessage());
        }
    }

}
