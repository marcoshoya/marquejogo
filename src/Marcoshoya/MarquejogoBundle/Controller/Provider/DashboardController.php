<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Provider;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DashboardController extends Controller
{

    /**
     * @Route("/", name="provider_dash")
     * @Template()
     */
    public function dashboardAction()
    {
        return array();
    }

    /**
     * @Route("/logout", name="provider_logout")
     * @Template()
     */
    public function logoutAction()
    {
        $this->get('security.context')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        return $this->redirect($this->generateUrl('provider_dash'));
    }

    /**
     * Sidebar action
     *
     * @param string    $view
     * @param styring   $item
     * @return string
     */
    public function sidebarAction($view, $item)
    {
        return $this->render('MarcoshoyaMarquejogoBundle:Provider/Dashboard:sidebar.html.twig', array(
                'view' => $view,
                'item' => $item
        ));
    }

    /**
     * Flash action
     *
     * @return string
     */
    public function flashAction()
    {
        return $this->render('MarcoshoyaMarquejogoBundle:Provider/Dashboard:flash.html.twig', array());
    }

}
