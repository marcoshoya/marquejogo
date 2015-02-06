<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Component\Search\SearchDTO;
use Marcoshoya\MarquejogoBundle\Form\SearchType;
use Marcoshoya\MarquejogoBundle\Helper\BundleHelper;

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
    public function sidebarAction($formSidebar)
    {
        return $this->render('MarcoshoyaMarquejogoBundle:Site/Search:sidebar.html.twig', array(
                'formSidebar' => $formSidebar,
        ));
    }

    /**
     * List all results from search
     *
     * @Route("/{city}", name="search_result")
     * @Template()
     */
    public function resultAction(Request $request, $city)
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

        $searchResult = $searchService->doSearch($searchDTO);

        $form = $this->createSearchForm($city);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                // gets the service
                $service = $this->get('marcoshoya_marquejogo.service.search');
                // form data
                $data = $form->getData();
                $service->setSearchSession($data);

                $slug = BundleHelper::sluggable($data['city']);

                return $this->redirect($this->generateUrl('search_result', array('city' => $slug)));
            }
        }

        return array(
            'city' => $city,
            'searchResult' => $searchResult,
            'formSidebar' => $form->createView(),
        );
    }

    /**
     * Creates the search form
     * 
     * @return SearchType
     */
    private function createSearchForm($city)
    {
        $service = $this->get('marcoshoya_marquejogo.service.search');
        $data = $service->getSearchData();

        $form = $this->createForm(new SearchType(), $data, array(
            'action' => $this->generateUrl('search_result', array('city' => $city)),
            'method' => 'POST',
        ));

        return $form;
    }

}
