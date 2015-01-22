<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Adm;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        return array();
    }

    /**
     * @Route("/logout", name="_marquejogo_adm_logout")
     * @Template()
     */
    public function logoutAction()
    {
        $this->get('security.context')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        return $this->redirect($this->generateUrl('_adm_dash'));
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
    
    /**
     * @Route("/location", name="location_type")
     */
    public function locationtypeAction(Request $request)
    {
        $state = $request->get('data');
        
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MarcoshoyaMarquejogoBundle:LocationCity')->findAllOrderedByName($state);
    
        $html = '';
        foreach ($entities as $locality) {
            $html = $html . sprintf("<option value=\"%d\">%s</option>", $locality->getId(), ucwords($locality->getName()));
        }
    
        return new Response($html);
    }

}
