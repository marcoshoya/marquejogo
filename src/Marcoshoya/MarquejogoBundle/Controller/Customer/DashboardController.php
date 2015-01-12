<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Entity\Customer;
use Marcoshoya\MarquejogoBundle\Form\CustomerType;

/**
 * Provides all functions for dashboard controller
 * 
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class DashboardController extends Controller
{

    /**
     * @Route("/", name="customer_dash")
     * @Template()
     */
    public function dashboardAction()
    {
        return array();
    }

    /**
     * @Route("/login", name="customer_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $entity = new Customer();
        $form = $this->createLoginForm($entity);
        $formRegister = $this->createRegisterForm($entity);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $auth = $this->doAuth($data);

                if ($auth) {

                    return $this->redirect($this->generateUrl('customer_dash'));
                }
            }
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
            'action' => $this->generateUrl('customer_login'),
            'method' => 'POST',
            'validation_groups' => array('login'),
        ));

        $form
            ->add('email', 'text', array(
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
     * Do the auth
     * 
     * @param Customer $entity
     * @return boolean
     * @throws AccessDeniedHttpException
     */
    private function doAuth(Customer $entity)
    {
        $entity->getPassword();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MarcoshoyaMarquejogoBundle:Customer')->findOneBy(array(
            'email' => $entity->getEmail(),
            'password' => $entity->getPassword(),
        ));

        if ($user instanceof Customer) {
            try {
                $providerKey = 'customer';
                $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

                $this->get('security.context')->setToken($token);

                $session = $this->getRequest()->getSession();
                $session->set('_security_main', serialize($token));

                if (!$this->get('security.context')->isGranted('ROLE_CUSTOMER')) {
                    throw new AccessDeniedHttpException();
                }

                return true;
            } catch (AccessDeniedHttpException $ex) {
                $this->get('security.context')->setToken(null);
                $this->get('logger')->error('{doAuth} Error: ' . $ex->getMessage());
                $this->get('session')->getFlashBag()->add('error', 'Credenciais inválidas');

                return false;
            }
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Usuário não encontrado');

            return false;
        }
    }
    
    /**
     * @Route("/doRegister", name="customer_doregister")
     * @Template("MarcoshoyaMarquejogoBundle:Customer/Dashboard:login.html.twig")
     */
    public function registerAction(Request $request)
    {
        $entity = new Customer();
        $form = $this->createLoginForm($entity);
        $formRegister = $this->createRegisterForm($entity);

        if ($request->getMethod() === 'POST') {
            $formRegister->handleRequest($request);
            if ($formRegister->isValid()) {
                
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
            ->add('email', 'text', array(
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
