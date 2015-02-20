<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Adm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        
        $date = new \DateTime();
        $books = $em->getRepository('MarcoshoyaMarquejogoBundle:Book')->countBookByMonth($date);
        
        $report = array(
            1 => array('name' => 'Janeiro', 'quantity' => 0), 
            2 => array('name' => 'Fevereiro', 'quantity' => 0), 
            3 => array('name' => 'Março', 'quantity' => 0), 
            4 => array('name' => 'Abril', 'quantity' => 0), 
            5 => array('name' => 'Maio', 'quantity' => 0), 
            6 => array('name' => 'Junho', 'quantity' => 0), 
            7 => array('name' => 'Julho', 'quantity' => 0), 
            8 => array('name' => 'Agosto', 'quantity' => 0), 
            9 => array('name' => 'Setebro', 'quantity' => 0), 
            10 => array('name' => 'Outubro', 'quantity' => 0), 
            11 => array('name' => 'Novembro', 'quantity' => 0), 
            12 => array('name' => 'Dezembro', 'quantity' => 0), 
        );
        
        
        foreach ($books as $book) {
            $report[$book['month']]['quantity'] = $book['quantity'];
        }
        
        return array('report' => $report);
    }
}
