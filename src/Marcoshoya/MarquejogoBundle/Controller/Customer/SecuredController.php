<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Marcoshoya\MarquejogoBundle\Entity\Customer;
use Marcoshoya\MarquejogoBundle\Form\CustomerType;

/**
 * SecuredController
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class SecuredController extends Controller
{
    /**
     * @Route("/login", name="customer_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $entity = new Customer();
        $form = $this->createLoginForm($entity);
        $formRegister = $this->createRegisterForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'formRegister' => $formRegister->createView(),
        );
    }

    /**
     * Do login
     * 
     * @Route("/doLogin", name="customer_dologin")
     * @Template("MarcoshoyaMarquejogoBundle:Customer/Secured:login.html.twig")
     * @Method({"POST"})
     */
    public function dologinAction(Request $request)
    {
        $entity = new Customer();
        $form = $this->createLoginForm($entity);
        $formRegister = $this->createRegisterForm($entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();

            $service = $this->get('marcoshoya_marquejogo.service.person');
            $user = $service->getUser($data);

            if (null !== $user) {

                return $this->redirect($this->generateUrl('customer_dash'));
            }
            
            $this->get('session')->getFlashBag()->add('error', 'Usuário não encontrado');
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'formRegister' => $formRegister->createView(),
        );
    }

    /**
     * @Route("/logout", name="customer_logout")
     * @Template()
     */
    public function logoutAction()
    {
        $this->get('security.context')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        return $this->redirect($this->generateUrl('marquejogo_homepage'));
    }

    /**
     * Create a login form
     *
     * @param Customer $entity
     * @return CustomerType
     */
    private function createLoginForm(Customer $entity)
    {
        $form = $this->createForm(new CustomerType(), $entity, array(
            'action' => $this->generateUrl('customer_dologin'),
            'method' => 'POST',
            'validation_groups' => array('login'),
        ));

        $form
            ->add('username', 'text', array(
                'required' => true,
                'trim' => true
            ))
            ->add('password', 'password', array(
                'required' => true,
                'trim' => true
            ))
            ->remove('name')
            ->remove('cpf')
            ->remove('gender')
            ->remove('position')
            ->remove('birthday')
            ->remove('phone')
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
     * @Route("/doRegister", name="customer_doregister")
     * @Template("MarcoshoyaMarquejogoBundle:Customer/Secured:login.html.twig")
     */
    public function registerAction(Request $request)
    {
        $entity = new Customer();
        $form = $this->createLoginForm($entity);
        $formRegister = $this->createRegisterForm($entity);

        if ($request->getMethod() === 'POST') {
            $formRegister->handleRequest($request);
            if ($formRegister->isValid()) {

                $data = $formRegister->getData();
                $this->get('session')->set('register_email', $data->getUsername());

                return $this->redirect($this->generateUrl('customer_new'));
            }
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'formRegister' => $formRegister->createView(),
        );
    }

    /**
     * Create a login form
     *
     * @param Customer $entity
     * @return CustomerType
     */
    private function createRegisterForm(Customer $entity)
    {
        $form = $this->createForm(new CustomerType(), $entity, array(
            'action' => $this->generateUrl('customer_doregister'),
            'method' => 'POST',
            'validation_groups' => array('unique'),
        ));

        $form
            ->add('username', 'text', array(
                'required' => true,
                'trim' => true
            ))
            ->remove('password')
            ->remove('name')
            ->remove('cpf')
            ->remove('gender')
            ->remove('position')
            ->remove('birthday')
            ->remove('phone')
            ->remove('address')
            ->remove('number')
            ->remove('complement')
            ->remove('neighborhood')
            ->remove('city')
            ->remove('state')
        ;

        return $form;
    }
}
