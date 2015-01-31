<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Entity\ScheduleItem;
use Marcoshoya\MarquejogoBundle\Form\ScheduleType;
use Marcoshoya\MarquejogoBundle\Form\BookingItemType;

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
     */
    public function showAction(Request $request, Provider $provider)
    {
        $serviceSearch = $this->get('marcoshoya_marquejogo.service.search');
        $serviceSchedule = $this->get('marcoshoya_marquejogo.service.schedule');
        $servicePicture = $this->get('marcoshoya_marquejogo.service.search');

        $products = array();
        
        // get pictures
        $picture = $servicePicture->getAllPicture($provider);

        // get search
        $searchDTO = $serviceSearch->getSearchSession();
        if (null !== $searchDTO) {
            // get products available to sell by date
            $products = $serviceSchedule->getallProductBySearch($provider, $searchDTO);
        }

        if (!count($products)) {
            $products = $serviceSchedule->getallProduct($provider);
            return $this->render('MarcoshoyaMarquejogoBundle:Site/Provider:noresult.html.twig', array(
                    'provider' => $provider,
                    'pictures' => $picture,
            ));
        }

        $form = $this->createScheduleForm($provider, $products);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $data = $form->getData();

                $service = $this->get('marcoshoya_marquejogo.service.book');
                $service->setBookSession($data);

                return $this->redirect($this->generateUrl('booking_information', array(
                            'id' => $data->getProvider()->getId()
                )));
            }
        }

        return $this->render('MarcoshoyaMarquejogoBundle:Site/Provider:show.html.twig', array(
                'provider' => $provider,
                'pictures' => $picture,
                'products' => $products,
                'form' => $form->createView()
        ));
    }

    /**
     * Creates a form to create a Provider entity.
     *
     * @param Provider $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createScheduleForm(Provider $provider, $products)
    {
        $em = $this->getDoctrine()->getManager();

        foreach ($products as $product) {
            /**
              $item = new ScheduleItem();
              //$item->setPrice(99);
              $item->setProviderProduct($product);
              $item->setSchedule($schedule);
              //$item->getProviderProduct()->add($product);
              $schedule->getScheduleItem()->add($item);
             * 
             */
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

}
