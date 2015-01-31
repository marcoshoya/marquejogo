<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Marcoshoya\MarquejogoBundle\Entity\Provider;

/**
 * BookingController implements all booking functions
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 * 
 * @Route("/reserva")
 */
class BookController extends Controller
{

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
     * @Route("/quadra{id}/informacao", name="booking_information")
     * @ParamConverter("provider", class="MarcoshoyaMarquejogoBundle:Provider")
     * @Template()
     */
    public function informationAction(Provider $provider)
    {

        $service = $this->get('marcoshoya_marquejogo.service.book');
        $book = $service->getBookSession($provider);

        if (null !== $book) {
            \Marcoshoya\MarquejogoBundle\Helper\BundleHelper::dump($book);
            
            //throw new \UnexpectedValueException("Book not found");
        }
        
        //$book->getProvider();

        return array(
            'book' => $book,
        );
    }

    /**
     * Do the booking
     *
     * @Route("/doBooking", name="do_booking")
     * @Method("POST")
     * @Template("MarcoshoyaMarquejogoBundle:Site/Book:new.html.twig")
     */
    public function dobookingAction(Request $request, Provider $provider)
    {
        return $this->redirect($this->generateUrl('booking_information'));
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
        return $this->render('MarcoshoyaMarquejogoBundle:Site/Book:progress.html.twig', array(
                'step' => $step
        ));
    }

    /**
     * Overview action
     * 
     * @return string
     */
    public function overviewAction($book)
    {
        $provider = $book->getProvider();
        
        // @todo: i don't know why, some data is missing from provider
        $em = $this->getDoctrine()->getManager();
        $provider = $em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->find($provider->getId());
        
        return $this->render('MarcoshoyaMarquejogoBundle:Site/Book:overview.html.twig', array(
            'provider' => $provider,
        ));
    }

}
