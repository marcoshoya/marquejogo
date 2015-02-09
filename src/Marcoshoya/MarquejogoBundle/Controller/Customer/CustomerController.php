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
use Doctrine\ORM\EntityRepository;

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
            $entity->setUsername($email);
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
     * @Route("/dados/editar", name="customer_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.context')->getToken()->getUser();
        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Customer')->find($user->getId());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
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
            'action' => $this->generateUrl('customer_update'),
            'method' => 'PUT',
            'validation_groups' => array('edit'),
        ));

        $form
            ->add('username', 'hidden')
            ->add('password', 'hidden')
            ->add('state', 'entity', array(
                'placeholder' => 'Escolha um estado',
                'data' => $entity->getState(),
                'class' => 'MarcoshoyaMarquejogoBundle:LocationState',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.country = 31')
                        ->orderBy('s.uf', 'ASC');
                },
            ))
        ;

        return $form;
    }

    /**
     * Edits an existing Customer entity.
     *
     * @Route("/dados/editar", name="customer_update")
     * @Method("PUT")
     * @Template("MarcoshoyaMarquejogoBundle:Customer/Customer:edit.html.twig")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Customer')->find($user->getId());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Dados atualizados com sucesso!');

            return $this->redirect($this->generateUrl('customer_edit'));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        );
    }

}
