<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Component\Search\SearchCollection;
use Marcoshoya\MarquejogoBundle\Component\Search\SearchDTO;
use Marcoshoya\MarquejogoBundle\Entity\LocationCity;
use Marcoshoya\MarquejogoBundle\Entity\Provider;

/**
 * SearchService improves all search funtions
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class SearchService extends BaseService
{

    public function setSearchSession()
    {
        
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
