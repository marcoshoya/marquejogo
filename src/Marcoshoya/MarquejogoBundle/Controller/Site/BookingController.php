<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * BookingController implements all booking functions
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 * 
 * @Route("/reserva")
 */
class BookingController extends Controller
{   
    /**
     * Do the booking
     *
     * @Route("/doBooking", name="do_booking")
     * @Method("POST")
     */
    public function dobookingAction()
    {
        return $this->redirect($this->generateUrl('booking_information'));
    }
    
    /**
     * User identification
     *
     * @Route("/identificacao", name="booking_login")
     * @Template()
     */
    public function loginAction()
    {
        return array();
    }
    
    /**
     * User information
     *
     * @Route("/informacao", name="booking_information")
     * @Template()
     */
    public function informationAction(Request $request)
    {
        $session = $this->get('session');
        
        if ($request->getMethod() === 'POST') {
            
            
            return $this->redirect($this->generateUrl('booking_payment'));
        }
        
        return array();
    }
    
    /**
     * Booking payment
     *
     * @Route("/pagamento", name="booking_payment")
     * @Template()
     */
    public function paymentAction()
    {
        return array();
    }
    
    /**
     * Progress action
     * 
     * @param int $step
     * @return string
     */
    public function progressAction($step)
    {
        return $this->render('MarcoshoyaMarquejogoBundle:Site/Booking:progress.html.twig', array(
            'step' => $step
        ));
    }
    
    /**
     * Overview action
     * 
     * @return string
     */
    public function overviewAction()
    {
        return $this->render('MarcoshoyaMarquejogoBundle:Site/Booking:overview.html.twig');
    }
    
}
