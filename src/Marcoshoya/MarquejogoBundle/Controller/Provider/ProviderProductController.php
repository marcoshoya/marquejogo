<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Entity\ProviderProduct;
use Marcoshoya\MarquejogoBundle\Form\ProviderProductType;

/**
 * ProviderProduct controller.
 *
 * @Route("/produtos")
 */
class ProviderProductController extends Controller
{

    /**
     * Lists all ProviderProduct entities.
     *
     * @Route("/", name="providerproduct")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MarcoshoyaMarquejogoBundle:ProviderProduct')->findBy(array(
            'provider' => $user
        ));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new ProviderProduct entity.
     *
     * @Route("/", name="providerproduct_create")
     * @Method("POST")
     * @Template("MarcoshoyaMarquejogoBundle:Provider/ProviderProduct:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ProviderProduct();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Quadra inserida com sucesso.');

            return $this->redirect($this->generateUrl('providerproduct'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ProviderProduct entity.
     *
     * @param ProviderProduct $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ProviderProduct $entity)
    {
        $form = $this->createForm(new ProviderProductType(), $entity, array(
            'action' => $this->generateUrl('providerproduct_create'),
            'method' => 'POST',
        ));
        
        $em = $this->getDoctrine()->getManager();

        $id = (int) $this->get('security.context')->getToken()->getUser()->getId();
        $provider = $em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->find($id);
        
        $form
            ->add('provider', 'entity_hidden', array(
                'class' => 'Marcoshoya\MarquejogoBundle\Entity\Provider',
                'data' => $provider,
                'data_class' => null,
            ))
        ;

        return $form;
    }

    /**
     * Displays a form to create a new ProviderProduct entity.
     *
     * @Route("/new", name="providerproduct_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ProviderProduct();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ProviderProduct entity.
     *
     * @Route("/{id}/edit", name="providerproduct_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:ProviderProduct')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProviderProduct entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        );
    }

    /**
     * Creates a form to edit a ProviderProduct entity.
     *
     * @param ProviderProduct $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ProviderProduct $entity)
    {
        $form = $this->createForm(new ProviderProductType(), $entity, array(
            'action' => $this->generateUrl('providerproduct_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        
        $em = $this->getDoctrine()->getManager();
        $id = (int) $this->get('security.context')->getToken()->getUser()->getId();
        $provider = $em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->find($id);
        
        $form
            ->add('provider', 'entity_hidden', array(
                'class' => 'Marcoshoya\MarquejogoBundle\Entity\Provider',
                'data' => $provider,
                'data_class' => null,
            ))
        ;

        return $form;
    }

    /**
     * Edits an existing ProviderProduct entity.
     *
     * @Route("/{id}", name="providerproduct_update")
     * @Method("PUT")
     * @Template("MarcoshoyaMarquejogoBundle:Provider/ProviderProduct:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:ProviderProduct')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProviderProduct entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Quadra atualizada com sucesso.');

            return $this->redirect($this->generateUrl('providerproduct_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        );
    }

}
