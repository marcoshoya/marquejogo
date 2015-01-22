<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

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
     */
    public function dosearhAction()
    {
       
        
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
