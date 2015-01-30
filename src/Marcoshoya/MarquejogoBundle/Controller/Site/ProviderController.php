<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use \Marcoshoya\MarquejogoBundle\Entity\ScheduleItem;
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
        $service = $this->get('marcoshoya_marquejogo.service.search');
        $picture = $service->getAllPicture($provider);

        $productService = $this->get('marcoshoya_marquejogo.service.product');
        $products = $productService->getallProduct($provider);

        $form = $this->createScheduleForm($provider, $products);
        
        if ($request->isMethod('POST')) {
            
            return $this->redirect($this->generateUrl('booking_information'));
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

        $schedule = $em->getRepository('MarcoshoyaMarquejogoBundle:Schedule')
            ->findOneBy(array('provider' => $provider));

        foreach ($products as $product) {
            $item = new ScheduleItem();
            //$item->setPrice(99);
            $item->setProviderProduct($product);
            $item->setSchedule($schedule);
            //$item->getProviderProduct()->add($product);
            $schedule->getScheduleItem()->add($item);
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
