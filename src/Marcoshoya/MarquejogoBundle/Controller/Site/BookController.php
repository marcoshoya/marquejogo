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
     * User information
     *
     * @Route("/quadra{id}/informacao", name="book_information")
     * @ParamConverter("provider", class="MarcoshoyaMarquejogoBundle:Provider")
     * @Template()
     */
    public function informationAction(Provider $provider)
    {
        $customer = $this->getCustomer();

        // gets book data on session
        $book = $this->getBook($provider);
        if (null === $book) {
            return $this->redirect($this->generateUrl('provider_show', array('id' => $provider->getId())));
        }

        $form = $this->createInformationForm($provider, $customer);
        $formLogin = $this->createLoginForm($provider, $customer);

        return array(
            'form' => $form->createView(),
            'formLogin' => $formLogin->createView(),
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
        $service = $this->get('marcoshoya_marquejogo.service.book');
        $book = $this->getBook($provider);
        if (null === $book) {
            return $this->redirect($this->generateUrl('provider_show', array('id' => $provider->getId())));
        }

        // customer data
        $customer = $this->getCustomer();

        $formLogin = $this->createLoginForm($provider, $customer);
        $form = $this->createInformationForm($provider, $customer);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $book->setCustomer($data);

            // persist on session
            $service->setBookSession($provider, $book);

            try {

                $book->setProvider($provider);
                $bookObject = $service->doBook($book);

                return $this->redirect($this->generateUrl('book_confirmation', array('id' => $provider->getId())));
            } catch (\RuntimeException $ex) {
                
            }
        }

        return array(
            'form' => $form->createView(),
            'formLogin' => $formLogin->createView(),
            'book' => $book,
        );
    }

    /**
     * Do login
     * 
     * @Route("/quadra{id}/doLogin", name="book_dologin")
     * @ParamConverter("provider", class="MarcoshoyaMarquejogoBundle:Provider")
     * @Template("MarcoshoyaMarquejogoBundle:Site/Book:information.html.twig")
     * @Method({"POST"})
     */
    public function dologinAction(Request $request, Provider $provider)
    {
        $book = $this->getBook($provider);
        if (null === $book) {
            return $this->redirect($this->generateUrl('provider_show', array('id' => $provider->getId())));
        }

        $entity = new Customer();
        $formLogin = $this->createLoginForm($provider, $entity);
        $form = $this->createInformationForm($provider, null);

        $formLogin->handleRequest($request);
        if ($formLogin->isValid()) {
            $data = $formLogin->getData();

            $service = $this->get('marcoshoya_marquejogo.service.person');
            $user = $service->getUser($data);

            if (null !== $user) {

                return $this->redirect($this->generateUrl('book_information', array(
                            'id' => $provider->getId()
                )));
            }

            $this->get('session')->getFlashBag()->add('error', 'Usuário não encontrado');
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'formLogin' => $formLogin->createView(),
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
        $book = $this->getBook($provider);
        if (null === $book) {
            return $this->redirect($this->generateUrl('provider_show', array('id' => $provider->getId())));
        }

        $date = $book->getDate();
        $dateTitle = sprintf('%s de %s as %dh', $date->format('d'), BundleHelper::monthTranslate($date->format('F')), $date->format('H')
        );

        $service = $this->get('marcoshoya_marquejogo.service.book');
        $service->clearBookSession($provider);

        $em = $this->getDoctrine()->getManager();
        $bookObject = $em->getRepository("MarcoshoyaMarquejogoBundle:Book")->find($book->getId());

        return array(
            'provider' => $provider,
            'dateTitle' => $dateTitle,
            'book' => $bookObject,
        );
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

    /**
     * Get book from session
     * 
     * @param Provider $provider
     * 
     * @return BookDTO
     * @throws \UnexpectedValueException
     */
    public function getBook(Provider $provider)
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
            return null;
        }
    }

    public function getCustomer()
    {
        $customer = null;
        if ($this->get('security.context')->isGranted('ROLE_CUSTOMER')) {
            $customer = $this->get('security.context')->getToken()->getUser();
        }

        return $customer;
    }

    /**
     * Create a login form
     *
     * @param Customer $entity
     * @return CustomerType
     */
    private function createLoginForm(Provider $provider, Customer $entity = null)
    {
        if (null === $entity) {
            $entity = new Customer();
        }

        $form = $this->createForm(new CustomerType(), $entity, array(
            'action' => $this->generateUrl('book_dologin', array('id' => $provider->getId())),
            'method' => 'POST',
            'validation_groups' => array('login'),
        ));

        $form
            ->add('username', 'text', array(
                'required' => true,
                'trim' => true
            ))
            ->add('password', 'password', array(
                'required' => true,
                'trim' => true
            ))
            ->remove('name')
            ->remove('cpf')
            ->remove('gender')
            ->remove('position')
            ->remove('birthday')
            ->remove('phone')
            ->remove('address')
            ->remove('number')
            ->remove('complement')
            ->remove('neighborhood')
            ->remove('city')
            ->remove('state')
        ;

        return $form;
    }

    /**
     * Create information form
     * 
     * @param Provider $provider
     * @param Customer $customer
     * 
     * @return Form
     */
    private function createInformationForm(Provider $provider, Customer $customer = null)
    {
        if (null === $customer) {
            $customer = new Customer();
            $customer->setPassword('password');
            $team = new Team();
            $team->setOwner($customer);
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
