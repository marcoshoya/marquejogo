<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Helper\BundleHelper;
use Marcoshoya\MarquejogoBundle\Entity\Schedule;
use Marcoshoya\MarquejogoBundle\Form\ScheduleType;

/**
 * ScheduleController controller.
 *
 * @author  Marcos Lazarin <marcoshoya at gmail dot com>
 *
 * @Route("/agenda")
 */
class ScheduleController extends Controller
{

    /**
     * It configures full schedule
     *
     * @Route("/{year}/{month}", name="schedule", requirements={
     *      "year": "\d+",
     *      "month": "\d+",
     * })
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
            '%s de %d', 
            BundleHelper::monthTranslate($currMonth->format('F')), 
            $currMonth->format('Y')
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
        /***
    
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
     * @Route("/{year}/{month}/dia/{day}", name="schedule_show", requirements={
     *      "year": "\d+",
     *      "month": "\d+",
     *      "day": "\d+",
     * })
     * @Method("GET")
     * @Template()
     */
    public function showAction($year, $month, $day)
    {
        
        
        return array(
            'year' => $year, 
            'month' => $month, 
            'day' => $day
        );
    }

    /**
     * Creates a new Schedule entity.
     *
     * @Route("/", name="schedule_create")
     * @Method("POST")
     * @Template("MarcoshoyaMarquejogoBundle:Schedule:new.html.twig")

      public function createAction(Request $request)
      {
      $entity = new Schedule();
      $form = $this->createCreateForm($entity);
      $form->handleRequest($request);

      if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('schedule_show', array('id' => $entity->getId())));
      }

      return array(
      'entity' => $entity,
      'form' => $form->createView(),
      );
      }
     */

    /**
     * Creates a form to create a Schedule entity.
     *
     * @param Schedule $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Schedule $entity)
    {
        $form = $this->createForm(new ScheduleType(), $entity, array(
            'action' => $this->generateUrl('schedule_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Schedule entity.
     *
     * @Route("/new", name="schedule_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Schedule();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Schedule entity.
     *
     * @Route("/{id}/edit", name="schedule_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Schedule')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Schedule entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Schedule entity.
     *
     * @param Schedule $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Schedule $entity)
    {
        $form = $this->createForm(new ScheduleType(), $entity, array(
            'action' => $this->generateUrl('schedule_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Schedule entity.
     *
     * @Route("/{id}", name="schedule_update")
     * @Method("PUT")
     * @Template("MarcoshoyaMarquejogoBundle:Schedule:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Schedule')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Schedule entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('schedule_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Schedule entity.
     *
     * @Route("/{id}", name="schedule_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Schedule')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Schedule entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('schedule'));
    }

    /**
     * Creates a form to delete a Schedule entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                ->setAction($this->generateUrl('schedule_delete', array('id' => $id)))
                ->setMethod('DELETE')
                ->add('submit', 'submit', array('label' => 'Delete'))
                ->getForm()
        ;
    }

}
