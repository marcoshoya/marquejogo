<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Entity\AdmUser;
use Marcoshoya\MarquejogoBundle\Form\AdmUserType;

class DashboardController extends Controller {

    /**
     * @Route("/", name="customer_dash")
     * @Template()
     */
    public function dashboardAction() {
        return array();
    }

    /**
     * @Route("/login", name="customer_login")
     * @Template()
     */
    public function loginAction(Request $request) {
        $entity = new AdmUser();
        $form = $this->createLoginForm($entity);

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
        );
    }

    /**
     * @Route("/logout", name="customer_logout")
     * @Template()
     */
    public function logoutAction() {
        $this->get('security.context')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        return $this->redirect($this->generateUrl('customer_dash'));
    }

    /**
     * Create a login form
     *
     * @param AdmUser $entity
     * @return AdmUserType
     */
    private function createLoginForm(AdmUser $entity) {
        $form = $this->createForm(new AdmUserType(), $entity, array(
            'action' => $this->generateUrl('customer_login'),
            'method' => 'POST',
        ));

        $form
            ->add('username', 'text', array(
                'required' => true,
                'trim' => true
            ))
            ->remove('email')
            ->remove('salt')
            ->remove('isActive')
        ;

        return $form;
    }

    /**
     * Do the auth
     * 
     * @param AdmUser $entity
     * @return boolean
     * @throws AccessDeniedHttpException
     */
    private function doAuth(AdmUser $entity) 
    {
        $entity->getPassword();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MarcoshoyaMarquejogoBundle:AdmUser')->findOneBy(array(
            'username' => $entity->getUserName(),
            'password' => $entity->getPassword(),
        ));

        if ($user instanceof AdmUser) {
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

}
