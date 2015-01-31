<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Component\Search\SearchCollection;
use Marcoshoya\MarquejogoBundle\Component\Search\SearchDTO;
use Marcoshoya\MarquejogoBundle\Entity\LocationCity;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
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
        
        if ($this->getSession()->has(SearchDTO::session)) {
            $this->getSession()->remove(SearchDTO::session);
        }
        
        $this->getSession()->set(SearchDTO::session, serialize($search));
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
        $city = $search->getAutocomplete()->getCity();

        $results = $this->getProviderByCity($city);

        $collection = new SearchCollection();
        if ($results) {
            foreach ($results as $provider) {
                $idx = $provider->getId();
                $collection->add($provider, $idx);
                // picture
                $picture = $this->getPicture($provider);
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
     * Get picture
     * @param Provider $provider
     * 
     * @return ProviderPicture
     */
    public function getPicture(Provider $provider)
    {
        try {

            $picture = $this->getEm()
                ->getRepository('MarcoshoyaMarquejogoBundle:ProviderPicture')
                ->findMainPicture($provider);

            return $picture;
        } catch (\Exception $ex) {
            $this->getLogger()->error("SearchService error: " . $ex->getMessage());
        }
    }
    
    /**
     * Get picture
     * @param Provider $provider
     * 
     * @return ProviderPicture
     */
    public function getAllPicture(Provider $provider)
    {
        try {

            $picture = $this->getEm()
                ->getRepository('MarcoshoyaMarquejogoBundle:ProviderPicture')
                ->findAllPicture($provider);

            return $picture;
        } catch (\Exception $ex) {
            $this->getLogger()->error("SearchService error: " . $ex->getMessage());
        }
    }

}
