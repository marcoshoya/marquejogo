<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
