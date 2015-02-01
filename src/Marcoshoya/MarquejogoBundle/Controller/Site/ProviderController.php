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

        // get products available to sell
        $products = $this->getProducts($provider, $searchDTO);

        // if there is no product, it renders another template
        if (!count($products)) {
            return $this->render('MarcoshoyaMarquejogoBundle:Site/Provider:noresult.html.twig', array(
                    'provider' => $provider,
                    'pictures' => $picture,
                    'formAvail' => $formAvail->createView(),
            ));
        }

        // submit the form to init book process
        $form = $this->createScheduleForm($provider, $products);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $data = $form->getData();

                $service = $this->get('marcoshoya_marquejogo.service.book');
                $service->setBookSession($data, $searchDTO);

                return $this->redirect($this->generateUrl('booking_information', array(
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

        // get products available to sell
        $products = $this->getProducts($provider, $searchDTO);

        // if there is no product, it renders another template
        if (!count($products)) {
            return $this->render('MarcoshoyaMarquejogoBundle:Site/Provider:noresult.html.twig', array(
                    'provider' => $provider,
                    'pictures' => $picture,
                    'formAvail' => $formAvail->createView(),
            ));
        }

        $form = $this->createScheduleForm($provider, $products);

        return array(
            'provider' => $provider,
            'pictures' => $picture,
            'products' => $products,
            'form' => $form->createView(),
            'formAvail' => $formAvail->createView(),
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
        $serviceSchedule = $this->get('marcoshoya_marquejogo.service.schedule');
        $products = array();

        // get search
        if (null !== $searchDTO) {
            $schedule = $this->getDoctrine()->getManager()
                ->getRepository('MarcoshoyaMarquejogoBundle:Schedule')
                ->findOneBy(array('provider' => $provider));
            // get products available to sell by date
            $products = $serviceSchedule->getallProductBySearch($schedule, $searchDTO);
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

}
