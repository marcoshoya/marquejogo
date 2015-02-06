<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Provides all functions for dashboard controller
 * 
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class DashboardController extends Controller
{

    /**
     * @Route("/", name="customer_dash")
     * @Method("GET")
     * @Template()
     */
    public function dashboardAction()
    {
        return array();
    }

}
