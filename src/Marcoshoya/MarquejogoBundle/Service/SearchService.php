<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Component\Search\SearchDTO;
use Marcoshoya\MarquejogoBundle\Entity\LocationCity;

/**
 * SearchService improves all search funtions
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class SearchService extends BaseService
{

    /**
     * Do the search
     *
     * @param SearchDTO $search
     *
     * @return array an array of Provider
     */
    public function doSeach(SearchDTO $search)
    {
        $city = $search->getAutocomplete()->getCity();

        return $this->getProviderByCity($city);
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

}
