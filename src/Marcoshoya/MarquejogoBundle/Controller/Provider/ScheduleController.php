<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Form\ScheduleItemType;
use Marcoshoya\MarquejogoBundle\Entity\ScheduleItem;
use Marcoshoya\MarquejogoBundle\Component\Schedule\ScheduleItem as ScheduleCompositeItem;
use Marcoshoya\MarquejogoBundle\Component\Schedule\ScheduleComposite;

/**
 * ScheduleController controller.
 *
 * @author  Marcos Lazarin <marcoshoya at gmail dot com>
 *
 * @Route("/agenda/{year}/{month}", requirements={
 *      "year": "\d+",
 *      "month": "\d+",
 * })
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
    public function indexAction($year, $month)
    {
        // initial vars
        $now = $schedule = $navbar = array();

        // loads service
        $scheduleService = $this->get('marcoshoya_marquejogo.service.schedule');

        // gets user on session
        $provider = $this->get('security.context')->getToken()->getUser();

        // Calendar start
        $now = $scheduleService->getNow();
        $firstDay = $scheduleService->getFirstdayMonth($year, $month);

        // navbar
        $navbar = $scheduleService->createNavbar($firstDay);

        $schedule = $scheduleService->createMonthCalendar($firstDay, $provider);

        /*         * *

          echo '<pre>';
          print_r($schedule);
          echo '</pre>';
         */
        return array(
            'schedule' => $schedule,
            'navbar' => $navbar,
            'now' => $now,
        );
    }

    /**
     * Displays a day from Schedule
     *
     * @Route("/dia/{day}", name="schedule_show", requirements={
     *      "day": "\d+",
     * })
     * @Method("GET")
     * @Template()
     */
    public function showAction($year, $month, $day)
    {
        // loads service
        $scheduleService = $this->get('marcoshoya_marquejogo.service.schedule');
        
        // starts doctrine manager
        $em = $this->getDoctrine()->getManager();

        // gets user on session
        $provider = $this->get('security.context')->getToken()->getUser();

        // dates for reference
        $dateInitial = new \DateTime(sprintf('%d-%d-%d', $year, $month, $day));
        $dateFinal = clone $dateInitial;
        $dateTemplate = clone $dateInitial;

        // nav bar
        $navbar = $scheduleService->createNavbar($dateTemplate, 'day');

        $dateInitial->modify('+6 hours');
        $dateFinal->modify('+1 day');

        // gets all active products
        $productList = $em->getRepository('MarcoshoyaMarquejogoBundle:ProviderProduct')->findBy(
            array(
                'provider' => $provider,
                'isActive' => true
            )
        );

        // main schedule
        $schedule = $em->getRepository('MarcoshoyaMarquejogoBundle:Schedule')->findOneBy(
            array(
                'provider' => $provider,
            )
        );

        // start composite
        $scheduleComposite = new ScheduleComposite();
        for ($hour = $dateInitial->getTimestamp(); $hour < $dateFinal->getTimestamp(); $hour = strtotime('+1 hour', $hour)) {

            $dateTime = new \DateTime(date('Y-m-d H:i:s', $hour));
            $item = new ScheduleCompositeItem();
            $item->setDate($dateTime);

            foreach ($productList as $k => $product) {
                $entity = $scheduleService->getItemByProductAndDate($schedule, $product, $dateTime);
                if (!$entity) {
                    $entity = new ScheduleItem();
                    $entity->setProviderProduct($product);
                    $entity->setDate($dateTime);
                    $entity->setPrice(0.00);
                    $entity->setAvailable(false);
                    $entity->setAlocated(0);
                }

                $item->addProduct($entity, $k);
            }

            $scheduleComposite->add($item, $hour);
        }

        return array(
            'schedule' => $scheduleComposite,
            'navbar' => $navbar
        );
    }

    /**
     * It configures full schedule
     *
     * @Route("/dia/{day}/quadra/{product}/hora/{hour}", name="schedule_edit", requirements={
     *      "day": "\d+",
     *      "product": "\d+",
     *      "hour": "\d+",
     * })
     * @Method("GET")
     * @Template()
     */
    public function editAction(Request $request)
    {
        // loads service
        $scheduleService = $this->get('marcoshoya_marquejogo.service.schedule');
        
        $entity = $this->createEntity($request);
        $form = $this->createEditForm($entity);

        $date = $entity->getDate();
        $navbar = $scheduleService->createNavbar($date);

        return array(
            'form' => $form->createView(),
            'entity' => $entity,
            'navbar' => $navbar
        );
    }

    /**
     * Edits an existing Schedule entity.
     *
     * @Route("/dia/{day}/quadra/{product}/hora/{hour}", name="schedule_update", requirements={
     *      "day": "\d+",
     *      "product": "\d+",
     *      "hour": "\d+",
     * })
     * @Method("PUT")
     * @Template("MarcoshoyaMarquejogoBundle:Provider/Schedule:edit.html.twig")
     */
    public function updateAction(Request $request)
    {
        // loads service
        $scheduleService = $this->get('marcoshoya_marquejogo.service.schedule');
        
        $em = $this->getDoctrine()->getManager();
        $entity = $this->createEntity($request);

        $date = $entity->getDate();
        $navbar = $scheduleService->createNavbar($date);

        $form = $this->createEditForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('schedule_show', array(
                        'year' => $entity->getDate()->format('Y'),
                        'month' => $entity->getDate()->format('m'),
                        'day' => $entity->getDate()->format('d'),
            )));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'navbar' => $navbar,
        );
    }

    private function createEntity(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // request data
        $year = $request->get('year');
        $month = $request->get('month');
        $day = $request->get('day');
        $hour = $request->get('hour');
        $prod = (int) $request->get('product');

        $date = new \DateTime(sprintf('%d-%d-%d %d:00:00', $year, $month, $day, $hour));

        $product = $em->getRepository('MarcoshoyaMarquejogoBundle:ProviderProduct')->find($prod);
        if (!$product instanceof \Marcoshoya\MarquejogoBundle\Entity\ProviderProduct) {
            throw $this->createNotFoundException('Unable to find ProviderProduct entity.');
        }

        $provider = $product->getProvider();
        $schedule = $em->getRepository('MarcoshoyaMarquejogoBundle:Schedule')->findOneBy(array(
            'provider' => $provider
        ));

        // check if there is the entity
        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:ScheduleItem')->findOneBy(
            array(
                'schedule' => $schedule,
                'date' => $date,
                'providerProduct' => $product
            )
        );

        if (!$entity instanceof ScheduleItem) {
            $entity = new ScheduleItem();
            $entity->setSchedule($schedule);
            $entity->setDate($date);
            $entity->setProviderProduct($product);
            $entity->setAlocated(0);
        }

        return $entity;
    }

    /**
     * Creates a form to edit a Schedule entity.
     *
     * @param Schedule $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ScheduleItem $entity)
    {
        $form = $this->createForm(new ScheduleItemType(), $entity, array(
            'action' => $this->generateUrl('schedule_update', array(
                'year' => $entity->getDate()->format('Y'),
                'month' => $entity->getDate()->format('m'),
                'day' => $entity->getDate()->format('d'),
                'hour' => $entity->getDate()->format('H'),
                'product' => $entity->getProviderProduct()->getId(),
            )),
            'method' => 'PUT',
        ));

        $form
            ->add('providerProduct', 'entity_hidden', array(
                'class' => 'Marcoshoya\MarquejogoBundle\Entity\ProviderProduct',
                'data' => $entity->getProviderProduct(),
                'data_class' => null,
            ))
            ->add('schedule', 'entity_hidden', array(
                'class' => 'Marcoshoya\MarquejogoBundle\Entity\Schedule',
                'data' => $entity->getSchedule(),
                'data_class' => null,
            ))
        ;

        return $form;
    }
}
