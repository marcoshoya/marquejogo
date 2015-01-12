<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Customer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Entity\Customer;
use Marcoshoya\MarquejogoBundle\Form\CustomerType;

/**
 * Customer controller
 * 
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class CustomerController extends Controller
{

    /**
     * Displays a form to create a new Customer entity.
     *
     * @Route("/cadastrar", name="customer_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Customer();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Customer entity.
     *
     * @Route("/cadastrar/", name="customer_create")
     * @Method("POST")
     * @Template("MarcoshoyaMarquejogoBundle:Customer/Customer:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Customer();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->clear();

            // do the auth
            $providerKey = 'customer';
            $token = new UsernamePasswordToken($entity, null, $providerKey, $entity->getRoles());

            $this->get('security.context')->setToken($token);
            $session = $this->getRequest()->getSession();
            $session->set('_security_main', serialize($token));

            return $this->redirect($this->generateUrl('customer_dash'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Customer entity.
     *
     * @param Customer $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Customer $entity)
    {
        if ($this->get('session')->has('register_email')) {
            $email = $this->get('session')->get('register_email');
            $entity->setEmail($email);
        }

        $form = $this->createForm(new CustomerType(), $entity, array(
            'action' => $this->generateUrl('customer_create'),
            'method' => 'POST',
            'validation_groups' => array('register'),
        ));

        $form
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

    /**
     * Displays a form to edit an existing Customer entity.
     *
     * @Route("/{id}/edit", name="dados_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Customer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
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
     * Creates a form to edit a Customer entity.
     *
     * @param Customer $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Customer $entity)
    {
        $form = $this->createForm(new CustomerType(), $entity, array(
            'action' => $this->generateUrl('dados_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Customer entity.
     *
     * @Route("/{id}", name="dados_update")
     * @Method("PUT")
     * @Template("MarcoshoyaMarquejogoBundle:Customer:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Customer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('dados_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Customer entity.
     *
     * @Route("/{id}", name="dados_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Customer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Customer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('dados'));
    }

    /**
     * Creates a form to delete a Customer entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                ->setAction($this->generateUrl('dados_delete', array('id' => $id)))
                ->setMethod('DELETE')
                ->add('submit', 'submit', array('label' => 'Delete'))
                ->getForm()
        ;
    }

}
