<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Component\Search\SearchDTO;

/**
 * Search controller.
 *
 * @Route("/busca")
 */
class SearchController extends Controller
{
    /**
     * Header action
     *
     * @return string
     */
    public function sidebarAction()
    {
        return $this->render('MarcoshoyaMarquejogoBundle:Site/Search:sidebar.html.twig');
    }

    /**
     * List all results from search
     *
     * @Route("/{city}", name="search_result")
     * @Method("GET")
     * @Template()
     */
    public function resultAction($city)
    {
        $searchService = $this->get('marcoshoya_marquejogo.service.search');
        $searchDTO = new SearchDTO();
        
        $session = $this->get('session');
        if ($session->has(SearchDTO::session)) {
            $object = $session->get(SearchDTO::session);
            $searchDTO = unserialize($object);
        } else {
            // in case of get without search
            $autocomplete = $this->get('marcoshoya_marquejogo.service.autocomplete')->getCity($city);
            $searchDTO->setAutocomplete($autocomplete);
        }
        
        $searchResult = $searchService->doSeach($searchDTO);
        
        
        return array(
            'city' => $city,
            'searchResult' => $searchResult
        );
    }
}
