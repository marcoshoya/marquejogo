<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Entity\ProviderProductPrice;
use Marcoshoya\MarquejogoBundle\Form\ProviderProductPriceType;

/**
 * ProviderProductPrice controller.
 *
 * @Route("/tarifa")
 */
class ProviderProductPriceController extends Controller
{

    /**
     * Lists all ProviderProductPrice entities.
     *
     * @Route("/", name="tarifa")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MarcoshoyaMarquejogoBundle:ProviderProductPrice')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new ProviderProductPrice entity.
     *
     * @Route("/", name="tarifa_create")
     * @Method("POST")
     * @Template("MarcoshoyaMarquejogoBundle:ProviderProductPrice:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ProviderProductPrice();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tarifa_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ProviderProductPrice entity.
     *
     * @param ProviderProductPrice $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ProviderProductPrice $entity)
    {
        $form = $this->createForm(new ProviderProductPriceType(), $entity, array(
            'action' => $this->generateUrl('tarifa_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ProviderProductPrice entity.
     *
     * @Route("/new", name="tarifa_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ProviderProductPrice();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ProviderProductPrice entity.
     *
     * @Route("/{id}", name="tarifa_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:ProviderProductPrice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProviderProductPrice entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ProviderProductPrice entity.
     *
     * @Route("/{id}/edit", name="tarifa_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:ProviderProductPrice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProviderProductPrice entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a ProviderProductPrice entity.
    *
    * @param ProviderProductPrice $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ProviderProductPrice $entity)
    {
        $form = $this->createForm(new ProviderProductPriceType(), $entity, array(
            'action' => $this->generateUrl('tarifa_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ProviderProductPrice entity.
     *
     * @Route("/{id}", name="tarifa_update")
     * @Method("PUT")
     * @Template("MarcoshoyaMarquejogoBundle:ProviderProductPrice:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:ProviderProductPrice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProviderProductPrice entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tarifa_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ProviderProductPrice entity.
     *
     * @Route("/{id}", name="tarifa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:ProviderProductPrice')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ProviderProductPrice entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tarifa'));
    }

    /**
     * Creates a form to delete a ProviderProductPrice entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tarifa_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
