<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Search controller.
 *
 * @Route("/search")
 */
class SearchController extends Controller
{
    /**
     * Do the search
     *
     * @Route("/doSearch", name="do_search")
     * @Method("POST")
     */
    public function dosearhAction()
    {
        return $this->redirect($this->generateUrl('search_result'));
    }

    /**
     * List all results from search
     *
     * @Route("/", name="search_result")
     * @Template()
     */
    public function resultAction()
    {
        return array();
    }
}
