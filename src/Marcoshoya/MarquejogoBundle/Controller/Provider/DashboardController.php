<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Provider;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Form\ProviderType;

class DashboardController extends Controller
{

    /**
     * @Route("/", name="provider_dash")
     * @Template()
     */
    public function dashboardAction()
    {
        return array();
    }

    /**
     * @Route("/login", name="provider_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $entity = new Provider();
        $form = $this->createLoginForm($entity);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $auth = $this->doAuth($data);

                if ($auth) {

                    return $this->redirect($this->generateUrl('provider_dash'));
                }
            }
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/logout", name="provider_logout")
     * @Template()
     */
    public function logoutAction()
    {
        $this->get('security.context')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        return $this->redirect($this->generateUrl('provider_dash'));
    }

    /**
     * Create a login form
     *
     * @param AdmUser $entity
     * @return AdmUserType
     */
    private function createLoginForm(Provider $entity)
    {
        $form = $this->createForm(new ProviderType(), $entity, array(
            'action' => $this->generateUrl('provider_login'),
            'method' => 'POST',
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
            ->remove('description')
            ->remove('cnpj')
            ->remove('phone')
            ->remove('address')
            ->remove('number')
            ->remove('complement')
            ->remove('neighborhood')
            ->remove('city')
            ->remove('state')
            ->remove('isActive')
        ;

        return $form;
    }

    /**
     * 
     * @param AdmUser $entity
     * @return boolean
     * @throws AccessDeniedHttpException
     */
    private function doAuth(Provider $entity)
    {

        $entity->getPassword();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->findOneBy(array(
            'email' => $entity->getEmail(),
            'password' => $entity->getPassword(),
        ));

        if ($user instanceof Provider) {
            try {
                $providerKey = 'provider';
                $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

                $this->get('security.context')->setToken($token);

                $session = $this->getRequest()->getSession();
                $session->set('_security_main', serialize($token));

                if (!$this->get('security.context')->isGranted(array('ROLE_ADMIN', 'ROLE_PROVIDER'))) {
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
     * Sidebar action
     *
     * @param string    $view
     * @param styring   $item
     * @return string
     */
    public function sidebarAction($view, $item)
    {
        return $this->render('MarcoshoyaMarquejogoBundle:Provider/Dashboard:sidebar.html.twig', array(
                'view' => $view,
                'item' => $item
        ));
    }

    /**
     * Flash action
     *
     * @return string
     */
    public function flashAction()
    {
        return $this->render('MarcoshoyaMarquejogoBundle:Provider/Dashboard:flash.html.twig', array());
    }

}
