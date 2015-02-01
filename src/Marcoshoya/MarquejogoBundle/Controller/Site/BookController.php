<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Marcoshoya\MarquejogoBundle\Helper\BundleHelper;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Entity\Customer;
use Marcoshoya\MarquejogoBundle\Form\CustomerType;

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
     * @Route("/quadra{id}/informacao", name="book_information")
     * @ParamConverter("provider", class="MarcoshoyaMarquejogoBundle:Provider")
     * @Template()
     */
    public function informationAction(Provider $provider)
    {

        $service = $this->get('marcoshoya_marquejogo.service.book');
        $book = $service->getBookSession($provider);

        if (null === $book) {
            $this->get('logger')->info("informationAction: sessao expidarada");
            return $this->redirect($this->generateUrl('provider_show', array('id' => $provider->getId())));
        }

        // \Marcoshoya\MarquejogoBundle\Helper\BundleHelper::dump($book);
        $customer = new Customer();
        $form = $this->createInformationForm($provider, $customer);

        return array(
            'form' => $form->createView(),
            'book' => $book,
        );
    }

    /**
     * Do the booking
     *
     * @Route("/quadra{id}/doBook", name="book_dobook")
     * @ParamConverter("provider", class="MarcoshoyaMarquejogoBundle:Provider")
     * @Template("MarcoshoyaMarquejogoBundle:Site/Book:information.html.twig")
     * @Method("POST")
     */
    public function dobookAction(Request $request, Provider $provider)
    {
        $customer = new Customer();
        $form = $this->createInformationForm($provider, $customer);
        
        $form->handleRequest($request);
        if ($form->isValid()) {
            
            
            return $this->redirect($this->generateUrl('book_confirmation'));
        }
        
        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Book confirmation
     *
     * @Route("/quadra{id}/confirmacao", name="book_confirmation")
     * @ParamConverter("provider", class="MarcoshoyaMarquejogoBundle:Provider")
     * @Template()
     * @Method("GET")
     */
    public function confirmationAction(Provider $provider)
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

        $date = $book->getDate();
        $dateTitle = sprintf('%s de %s as %dh', $date->format('d'), BundleHelper::monthTranslate($date->format('F')), $date->format('H')
        );

        //\Marcoshoya\MarquejogoBundle\Helper\BundleHelper::dump($book->getAllItem());


        return $this->render('MarcoshoyaMarquejogoBundle:Site/Book:overview.html.twig', array(
                'provider' => $provider,
                'dateTitle' => $dateTitle,
                'book' => $book,
        ));
    }

    private function createInformationForm(Provider $provider, Customer $entity)
    {
        $form = $this->createForm(new CustomerType(), $entity, array(
            'action' => $this->generateUrl('provider_show', array('id' => $provider->getId())),
            'method' => 'POST',
        ));

        return $form;
    }

}
