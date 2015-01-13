<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Helper\BundleHelper;

/**
 * ScheduleController controller.
 *
 * @author  Marcos Lazarin <marcoshoya at gmail dot com>
 * 
 * @Route("/agenda")
 */
class ScheduleController extends Controller
{

    /**
     * It configures full schedule
     *
     * @Route("/", name="schedule")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        // initial vars
        $now = $schedule = $days = array();
        $current = new \DateTime();

        // now data
        $now['day'] = $current->format('j');
        $now['month'] = BundleHelper::monthTranslate($current->format('F'));
        $now['year'] = $current->format('Y');

        $first = new \DateTime(date('Y-m-01'));
        $last = new \DateTime(date('Y-m-t'));
        
        // start calendar
        if ($first->format('w') != 0) {
            $first->modify('last Sunday');
        }
        
        $d1 = clone $first;
        $d2 = clone $d1;
        $d2->modify('+6 weeks');

        $week = $day = 1;
        for ($aux = $d1->getTimestamp(); $aux < $d2->getTimestamp(); $aux = strtotime('+1 day', $aux)) {
            $datetime = new \DateTime(date('Y-m-d', $aux));
            $schedule[$week][$day] = $datetime;
            
            if ($datetime->format('Y-m-d') === $last->format('Y-m-d') && $day === 7) {
                break;
            }
            
            $day++;    
            if ($day > 7) {
                $day = 1;
                $week++;
            }
        }
        /** 
        echo '<pre>';
        print_r($schedule);
        echo '</pre>';
                * 
         */
        return array('schedule' => $schedule);
    }

}
