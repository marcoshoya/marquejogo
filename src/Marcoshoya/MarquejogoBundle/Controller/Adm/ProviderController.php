<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Adm;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Form\ProviderType;
use Marcoshoya\MarquejogoBundle\Form\Type\LocationType;

/**
 * Provider controller.
 *
 * @Route("/provider")
 */
class ProviderController extends Controller
{

    /**
     * Lists all Provider entities.
     *
     * @Route("/", name="provider")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Provider entity.
     *
     * @Route("/", name="provider_create")
     * @Method("POST")
     * @Template("MarcoshoyaMarquejogoBundle:Adm/Provider:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Provider();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $city = $form['statecity']['city']->getData();
            $entity->setCity($city);

            $service = $this->get('marcoshoya_marquejogo.service.provider');
            $service->update($entity);

            $this->get('session')->getFlashBag()->add('success', 'Fornecedor inserido com sucesso.');

            return $this->redirect($this->generateUrl('provider'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Provider entity.
     *
     * @param Provider $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Provider $entity)
    {
        $form = $this->createForm(new ProviderType(), $entity, array(
            'action' => $this->generateUrl('provider_create'),
            'method' => 'POST',
        ));

        $form
            ->add('name', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('description', 'textarea', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('cnpj', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('phone', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('address', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('number', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('neighborhood', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('statecity', new LocationType($entity), array(
                'mapped' => false,
            ))
            ->remove('city')
        ;

        return $form;
    }

    /**
     * Displays a form to create a new Provider entity.
     *
     * @Route("/new", name="provider_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Provider();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Provider entity.
     *
     * @Route("/{id}/edit", name="provider_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provider entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Provider entity.
     *
     * @param Provider $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Provider $entity)
    {
        $form = $this->createForm(new ProviderType(), $entity, array(
            'action' => $this->generateUrl('provider_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form
            ->add('name', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('description', 'textarea', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('cnpj', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('phone', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('address', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('number', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('neighborhood', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('statecity', new LocationType($entity), array(
                'mapped' => false,
            ))
            ->remove('city')
        ;

        return $form;
    }

    /**
     * Edits an existing Provider entity.
     *
     * @Route("/{id}", name="provider_update")
     * @Method("PUT")
     * @Template("MarcoshoyaMarquejogoBundle:Adm/Provider:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provider entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $city = $editForm['statecity']['city']->getData();
            $entity->setCity($city);

            $service = $this->get('marcoshoya_marquejogo.service.provider');
            $service->update($entity);

            $this->get('session')->getFlashBag()->add('success', 'Fornecedor atualizado com sucesso.');

            return $this->redirect($this->generateUrl('provider'));
        } else {
            //print_r($editForm->getErrorsAsString()); exit;
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Provider entity.
     *
     * @Route("/{id}/delete", name="provider_delete")
     */
    public function deleteAction($id)
    {
        try {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Provider entity.');
            }

            $em->remove($entity);
            $em->flush();
        } catch (\Exception $e) {
            $this->get('logger')->error('{Provider} Error: ' . $e->getMessage());
        }

        return $this->redirect($this->generateUrl('provider'));
    }

}
