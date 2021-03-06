<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Adm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * DashboardController
 * 
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class DashboardController extends Controller
{

    /**
     * @Route("/", name="_adm_dash")
     * @Template()
     */
    public function dashboardAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $until = new \DateTime();
        $from = clone $until;
        $from->modify("-30 days");
        
        $entities = $em->getRepository("MarcoshoyaMarquejogoBundle:Book")
            ->findByPeriod($from, $until);
        
        return array('entities' => $entities);
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
        return $this->render('MarcoshoyaMarquejogoBundle:Adm/Dashboard:sidebar.html.twig', array(
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
        return $this->render('MarcoshoyaMarquejogoBundle:Adm/Dashboard:flash.html.twig', array());
    }

}
