<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Adm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * ReportController
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 * 
 * @Route("/relatorio")
 */
class ReportController extends Controller
{
    /**
     * @Route("/reserva", name="adm_report_book")
     * @Method("GET")
     * @Template()
     */
    public function bookAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $until = new \DateTime();
        $from = clone $until;
        $from->modify("-30 days");
        
        $entities = $em->getRepository("MarcoshoyaMarquejogoBundle:Book")
            ->findByPeriod($from, $until);
        
        return array('entities' => $entities);
    }
}
