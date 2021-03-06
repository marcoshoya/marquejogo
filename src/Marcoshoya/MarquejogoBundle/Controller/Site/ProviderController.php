<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Marcoshoya\MarquejogoBundle\Component\Search\SearchDTO;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Entity\Schedule;
use Marcoshoya\MarquejogoBundle\Form\ScheduleType;
use Marcoshoya\MarquejogoBundle\Form\BookingItemType;
use Marcoshoya\MarquejogoBundle\Form\AvailType;
use Marcoshoya\MarquejogoBundle\Form\SearchType;

/**
 * ProviderController implements all provider functions on site
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ProviderController extends Controller
{

    /**
     * List all results from search
     *
     * @Route("/quadra{id}", name="provider_show")
     * @ParamConverter("provider", class="MarcoshoyaMarquejogoBundle:Provider")
     * @Template()
     */
    public function showAction(Request $request, Provider $provider)
    {
        // load services
        $servicePerson = $this->get('marcoshoya_marquejogo.service.person');
        $serviceSearch = $this->get('marcoshoya_marquejogo.service.search');

        // search data
        $searchDTO = $serviceSearch->getSearchSession();

        // get pictures
        $picture = $servicePerson->getAllPicture($provider);

        // form avail
        $formAvail = $this->createAvailForm($provider, $serviceSearch->getSearchData());

        // form sidebar
        $formSidebar = $this->createSidebarForm($provider);

        // get products available to sell
        $products = $this->getProducts($provider, $searchDTO);

        // if there is no product, it renders another template
        if (!count($products)) {
            return $this->render('MarcoshoyaMarquejogoBundle:Site/Provider:noresult.html.twig', array(
                    'provider' => $provider,
                    'pictures' => $picture,
                    'formAvail' => $formAvail->createView(),
                    'formSidebar' => $formSidebar->createView(),
            ));
        }

        // submit the form to init book process
        $form = $this->createScheduleForm($provider, $products);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $data = $form->getData();

                $service = $this->get('marcoshoya_marquejogo.service.book');
                $service->createBookSession($data, $searchDTO);

                return $this->redirect($this->generateUrl('book_information', array(
                            'id' => $data->getProvider()->getId()
                )));
            }
        }

        return array(
            'provider' => $provider,
            'pictures' => $picture,
            'products' => $products,
            'form' => $form->createView(),
            'formAvail' => $formAvail->createView(),
            'formSidebar' => $formSidebar->createView(),
        );
    }

    /**
     * @Route("/quadra{id}/doAvail", name="provider_doavail")
     * @Method("POST")
     * @ParamConverter("provider", class="MarcoshoyaMarquejogoBundle:Provider")
     * @Template("MarcoshoyaMarquejogoBundle:Site/Provider:show.html.twig")
     */
    public function doavailAction(Request $request, Provider $provider)
    {
        // load services
        $servicePerson = $this->get('marcoshoya_marquejogo.service.person');
        $serviceSearch = $this->get('marcoshoya_marquejogo.service.search');

        // search data
        $searchDTO = $serviceSearch->getSearchSession();

        // get pictures
        $picture = $servicePerson->getAllPicture($provider);

        // form avail
        $formAvail = $this->createAvailForm($provider, $serviceSearch->getSearchData());

        $formAvail->handleRequest($request);
        if ($formAvail->isValid()) {

            $data = $formAvail->getData();
            $serviceSearch->setSearchSession($data);

            return $this->redirect($this->generateUrl('provider_show', array('id' => $provider->getId())));
        }

        // form sidebar
        $formSidebar = $this->createSidebarForm($provider);

        // get products available to sell
        $products = $this->getProducts($provider, $searchDTO);

        // if there is no product, it renders another template
        if (!count($products)) {
            return $this->render('MarcoshoyaMarquejogoBundle:Site/Provider:noresult.html.twig', array(
                    'provider' => $provider,
                    'pictures' => $picture,
                    'formAvail' => $formAvail->createView(),
                    'formSidebar' => $formSidebar->createView(),
            ));
        }

        $form = $this->createScheduleForm($provider, $products);

        return array(
            'provider' => $provider,
            'pictures' => $picture,
            'products' => $products,
            'form' => $form->createView(),
            'formAvail' => $formAvail->createView(),
            'formSidebar' => $formSidebar->createView(),
        );
    }

    /**
     * @Route("/quadra{id}/doSearch", name="provider_dosearch")
     * @Method("POST")
     * @ParamConverter("provider", class="MarcoshoyaMarquejogoBundle:Provider")
     * @Template("MarcoshoyaMarquejogoBundle:Site/Provider:show.html.twig")
     */
    public function dosearchAction(Request $request, Provider $provider)
    {
        // load services
        $servicePerson = $this->get('marcoshoya_marquejogo.service.person');
        $serviceSearch = $this->get('marcoshoya_marquejogo.service.search');

        // search data
        $searchDTO = $serviceSearch->getSearchSession();

        // get pictures
        $picture = $servicePerson->getAllPicture($provider);

        // form avail
        $formAvail = $this->createAvailForm($provider, $serviceSearch->getSearchData());

        // sidebar
        $formSidebar = $this->createSidebarForm($provider);

        $formSidebar->handleRequest($request);
        if ($formSidebar->isValid()) {

            // gets the service
            $service = $this->get('marcoshoya_marquejogo.service.search');
            // form data
            $data = $formSidebar->getData();
            $search = $service->setSearchSession($data);

            return $this->redirect($this->generateUrl('search_result', array('city' => $search->getSlug())));
        }

        // get products available to sell
        $products = $this->getProducts($provider, $searchDTO);

        // if there is no product, it renders another template
        if (!count($products)) {
            return $this->render('MarcoshoyaMarquejogoBundle:Site/Provider:noresult.html.twig', array(
                    'provider' => $provider,
                    'pictures' => $picture,
                    'formAvail' => $formAvail->createView(),
                    'formSidebar' => $formSidebar->createView(),
            ));
        }

        $form = $this->createScheduleForm($provider, $products);

        return array(
            'provider' => $provider,
            'pictures' => $picture,
            'products' => $products,
            'form' => $form->createView(),
            'formAvail' => $formAvail->createView(),
            'formSidebar' => $formSidebar->createView(),
        );
    }

    /**
     * Get products to sell
     *
     * @param Provider $provider
     * @param SearchDTO $searchDTO
     * @return array
     */
    private function getProducts(Provider $provider, SearchDTO $searchDTO = null)
    {
        $searchSchedule = $this->get('marcoshoya_marquejogo.service.search');
        $products = array();

        // get search
        if (null !== $searchDTO) {
            $schedule = $this->getDoctrine()->getManager()
                ->getRepository('MarcoshoyaMarquejogoBundle:Schedule')
                ->findOneBy(array('provider' => $provider));
            // get products available to sell by date
            $products = $searchSchedule->getallProductBySearch($schedule, $searchDTO);
        }

        return $products;
    }

    /**
     * Creates a form to create a Provider entity.
     *
     * @param Provider $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createScheduleForm(Provider $provider, $products = array())
    {
        $schedule = new Schedule();
        $schedule->setProvider($provider);

        foreach ($products as $product) {
            $schedule->getScheduleItem()->add($product);
        }

        $form = $this->createForm(new ScheduleType(), $schedule, array(
            'action' => $this->generateUrl('provider_show', array('id' => $provider->getId())),
            'method' => 'POST',
        ));

        $form
            ->add('scheduleItem', 'collection', array(
                'type' => new BookingItemType(),
            ))
        ;

        return $form;
    }

    /**
     * Creates a form to create a Provider entity.
     *
     * @param Provider $entity The entity
     * @param array $searchData
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createAvailForm(Provider $provider, $searchData = array())
    {
        $searchData['provider'] = $provider->getId();
        $form = $this->createForm(new AvailType(), $searchData, array(
            'action' => $this->generateUrl('provider_doavail', array('id' => $provider->getId())),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Creates the search form
     *
     * @return SearchType
     */
    private function createSidebarForm(Provider $provider)
    {
        $service = $this->get('marcoshoya_marquejogo.service.search');
        $data = $service->getSearchData();

        $form = $this->createForm(new SearchType(), $data, array(
            'action' => $this->generateUrl('provider_dosearch', array('id' => $provider->getId())),
            'method' => 'POST',
        ));

        return $form;
    }

}
