<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Adm;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Entity\AdmUser;
use Marcoshoya\MarquejogoBundle\Form\AdmUserType;

/**
 * AdmUser controller.
 *
 * @Route("/admuser")
 */
class AdmUserController extends Controller
{
    /**
     * Lists all AdmUser entities.
     *
     * @Route("/", name="admuser")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MarcoshoyaMarquejogoBundle:AdmUser')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new AdmUser entity.
     *
     * @Route("/", name="admuser_create")
     * @Method("POST")
     * @Template("MarcoshoyaMarquejogoBundle:Adm/AdmUser:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new AdmUser();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Usuário inserido com sucesso.');

            return $this->redirect($this->generateUrl('admuser'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a AdmUser entity.
     *
     * @param AdmUser $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AdmUser $entity)
    {
        $form = $this->createForm(new AdmUserType(), $entity, array(
            'action' => $this->generateUrl('admuser_create'),
            'method' => 'POST',
        ));
        
        $form->remove('salt');

        return $form;
    }

    /**
     * Displays a form to create a new AdmUser entity.
     *
     * @Route("/new", name="admuser_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new AdmUser();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing AdmUser entity.
     *
     * @Route("/{id}/edit", name="admuser_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:AdmUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdmUser entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a AdmUser entity.
    *
    * @param AdmUser $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(AdmUser $entity)
    {
        $form = $this->createForm(new AdmUserType(), $entity, array(
            'action' => $this->generateUrl('admuser_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        
        $form->remove('salt');

        return $form;
    }
    /**
     * Edits an existing AdmUser entity.
     *
     * @Route("/{id}", name="admuser_update")
     * @Method("PUT")
     * @Template("MarcoshoyaMarquejogoBundle:Adm/AdmUser:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:AdmUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdmUser entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Usuário atualizado com sucesso.');

            return $this->redirect($this->generateUrl('admuser'));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a AdmUser entity.
     *
     * @Route("/{id}/delete", name="admuser_delete")
     */
    public function deleteAction($id)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:AdmUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AdmUser entity.');
            }

            $em->remove($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Usuário excluido com sucesso.');
            
        } catch (\Exception $e) {
            $this->get('logger')->error('{AdmUser} Error: ' . $e->getMessage());
        }

        return $this->redirect($this->generateUrl('admuser'));
    }
}
