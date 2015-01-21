<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Form\SearchType;
use Marcoshoya\MarquejogoBundle\Helper\BundleHelper;

/**
 * Search controller.
 *
 * @Route("/search")
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
     * Do the search
     *
     * @Route("/doSearch", name="do_search")
     * @Method("POST")
     * @Template("MarcoshoyaMarquejogoBundle:Site/Main:main.html.twig")
     */
    public function dosearhAction(Request $request)
    {
        $form = $this->createForm(new SearchType(), null, array(
            'action' => $this->generateUrl('do_search'),
            'method' => 'POST',
        ));
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $data = $form->getData();
            
            $city = BundleHelper::sluggable($data['city']);

            return $this->redirect($this->generateUrl('search_result', array('city' => $city)));
        }
        
        return array();
    }

    /**
     * List all results from search
     *
     * @Route("/{city}", name="search_result")
     * @Template()
     */
    public function resultAction($city)
    {
        return array('city' => $city);
    }
}
