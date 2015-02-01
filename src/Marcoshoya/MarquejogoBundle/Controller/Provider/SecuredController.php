<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Form\ProviderType;

/**
 * SecuredController
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 *
 */
class SecuredController extends Controller
{

    /**
     * @Route("/login", name="provider_login")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $entity = new Provider();
        $form = $this->createLoginForm($entity);

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/doLogin", name="provider_dologin")
     * @Method("POST")
     * @Template("MarcoshoyaMarquejogoBundle:Provider/Secured:index.html.twig")
     */
    public function dologinAction(Request $request)
    {
        $entity = new Provider();
        $form = $this->createLoginForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            // gets service container
            $service = $this->get('marcoshoya_marquejogo.service.person');
            $user = $service->getUser($data);

            if (null !== $user) {
                $this->doAuth($user);

                return $this->redirect($this->generateUrl('provider_dash'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
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
            'action' => $this->generateUrl('provider_dologin'),
            'method' => 'POST',
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
        try {
            $providerKey = 'provider';
            $token = new UsernamePasswordToken($entity, null, $providerKey, $entity->getRoles());

            $this->get('security.context')->setToken($token);

            $session = $this->getRequest()->getSession();
            $session->set('_security_main', serialize($token));

            if (!$this->get('security.context')->isGranted(array('ROLE_PROVIDER'))) {
                throw new AccessDeniedHttpException();
            }

        } catch (AccessDeniedHttpException $ex) {
            $this->get('security.context')->setToken(null);
            $this->get('logger')->error('{doAuth} Error: ' . $ex->getMessage());
            $this->get('session')->getFlashBag()->add('error', 'Credenciais inválidas');
        }
    }

}
