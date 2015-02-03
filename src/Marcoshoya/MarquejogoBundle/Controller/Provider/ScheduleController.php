<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Helper\BundleHelper;
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
        $now = $schedule = $days = $navbar = array();

        // now data
        $current = new \DateTime();
        $now['day'] = $current->format('j');
        $now['month'] = BundleHelper::monthTranslate($current->format('F'));
        $now['year'] = $current->format('Y');

        // Calendar start
        $firstDay = new \DateTime(date(sprintf('%d-%d-01', $year, $month)));
        $lastDay = clone $firstDay;
        $lastDay->modify('+1 month');
        $lastDay->modify('-1 day'); // bug with february
        // nav bar above calendar
        $currMonth = clone $firstDay;
        $prevMonth = clone $firstDay;
        $prevMonth->modify('-1 month');
        $nextMonth = clone $firstDay;
        $nextMonth->modify('+1 month');

        $navbar['curr']['title'] = sprintf(
            '%s de %d', BundleHelper::monthTranslate($currMonth->format('F')), $currMonth->format('Y')
        );
        $navbar['curr']['date'] = $currMonth;
        $navbar['curr']['month'] = $currMonth->format('m');
        $navbar['curr']['year'] = $currMonth->format('Y');
        $navbar['prev']['month'] = $prevMonth->format('m');
        $navbar['prev']['year'] = $prevMonth->format('Y');
        $navbar['next']['month'] = $nextMonth->format('m');
        $navbar['next']['year'] = $nextMonth->format('Y');

        // check if fist day is not sunday
        if ($firstDay->format('w') != 0) {
            $firstDay->modify('last Sunday');
        }

        // get next 6 weeks
        $d1 = clone $firstDay;
        $d2 = clone $d1;
        $d2->modify('+6 weeks');

        $week = $day = 1;

        // get day by week
        for ($aux = $d1->getTimestamp(); $aux < $d2->getTimestamp(); $aux = strtotime('+1 day', $aux)) {
            $datetime = new \DateTime(date('Y-m-d', $aux));
            $schedule[$week][$day] = $datetime;

            // check if date from loop is the last day of the month and if it is not saturday
            if ($datetime->format('Y-m-d') === $lastDay->format('Y-m-d') && $day === 7) {
                break;
            }

            // adjusts to print 5 weeks
            if ($datetime->format('Y-m-d') > $lastDay->format('Y-m-d') && $day === 1) {
                unset($schedule[$week]);
                break;
            }

            // controls push on array of week, to organize it
            $day++;
            if ($day > 7) {
                $day = 1;
                $week++;
            }
        }
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
        // starts doctrine manager
        $em = $this->getDoctrine()->getManager();

        // gets user on session
        $provider = $this->get('security.context')->getToken()->getUser();

        

        // dates for reference
        $dateInitial = new \DateTime(sprintf('%d-%d-%d', $year, $month, $day));
        $dateFinal = clone $dateInitial;

        // page title
        $dateTitle = sprintf(
            '%s, %d de %s de %d', // title format
            BundleHelper::weekdayTranslate($dateInitial->format('w')), // day of week
            $dateInitial->format('d'), // day number
            BundleHelper::monthTranslate($dateInitial->format('F')), // name of month
            $dateInitial->format('Y')
        );

        
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
        
        // loads service
        $scheduleService = $this->get('marcoshoya_marquejogo.service.schedule');
        
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
            'dateTitle' => $dateTitle,
            'schedule' => $scheduleComposite,
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
        $entity = $this->createEntity($request);
        $form = $this->createEditForm($entity);

        return array(
            'form' => $form->createView(),
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
        $em = $this->getDoctrine()->getManager();

        $entity = $this->createEntity($request);

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
