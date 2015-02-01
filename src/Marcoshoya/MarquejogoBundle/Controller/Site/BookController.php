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
use Marcoshoya\MarquejogoBundle\Entity\Team;
use Marcoshoya\MarquejogoBundle\Form\CustomerType;
use Marcoshoya\MarquejogoBundle\Form\TeamType;

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
     * Get book from session
     * 
     * @param Provider $provider
     * 
     * @return BookDTO
     * @throws \UnexpectedValueException
     */
    private function getBook(Provider $provider)
    {
        $service = $this->get('marcoshoya_marquejogo.service.book');
        try {

            $book = $service->getBookSession($provider);
            if (null === $book) {
                throw new \UnexpectedValueException("Book not found");
            }
            
            return $book;
            
        } catch (\UnexpectedValueException $ex) {
            $this->get('logger')->info("BookController error: " . $ex->getMessage());
            return $this->redirect($this->generateUrl('provider_show', array('id' => $provider->getId())));
        }
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
        $book = $this->getBook($provider);

        // \Marcoshoya\MarquejogoBundle\Helper\BundleHelper::dump($book);

        $form = $this->createInformationForm($provider, $customer = null);

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
        $book = $this->getBook($provider);
        
        $customer = new Customer();
        $form = $this->createInformationForm($provider, $customer);

        $form->handleRequest($request);
        if ($form->isValid()) {


            return $this->redirect($this->generateUrl('book_confirmation'));
        }

        return array(
            'form' => $form->createView(),
            'book' => $book,
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

    private function createInformationForm(Provider $provider, Customer $customer = null)
    {
        if (null === $customer) {
            $customer = new Customer();
            $team = new Team();
            $customer->getTeam()->add($team);
        }

        $form = $this->createForm(new CustomerType(), $customer, array(
            'action' => $this->generateUrl('book_dobook', array('id' => $provider->getId())),
            'method' => 'POST',
            'validation_groups' => array('book'),
        ));

        $form
            ->add('username')
            ->add('password', 'hidden')
            ->add('name')
            ->add('phone')
            ->add('team', 'collection', array(
                'type' => new TeamType(),
                'allow_add' => true,
            ))
            ->remove('cpf')
            ->remove('gender')
            ->remove('position')
            ->remove('birthday')
            ->remove('address')
            ->remove('number')
            ->remove('complement')
            ->remove('neighborhood')
            ->remove('city')
            ->remove('state')
        ;

        return $form;
    }

}
