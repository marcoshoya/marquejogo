<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Adm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Marcoshoya\MarquejogoBundle\Entity\AdmUser;
use Marcoshoya\MarquejogoBundle\Form\AdmUserType;

/**
 * SecuredController
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class SecuredController extends Controller
{
    /**
     * @Route("/login", name="adm_login")
     * @Template()
     */
    public function indexAction()
    {
        $entity = new AdmUser();
        $form = $this->createLoginForm($entity);

        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * @Route("/doLogin", name="adm_dologin")
     * @Method("POST")
     * @Template("MarcoshoyaMarquejogoBundle:Provider/Secured:index.html.twig")
     */
    public function dologinAction(Request $request)
    {
        $entity = new AdmUser();
        $form = $this->createLoginForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            // gets service container
            $service = $this->get('marcoshoya_marquejogo.service.personservice');
            $user = $service->getUser($data);

            if (null !== $user) {
                $this->doAuth($user);

                //return $this->redirect($this->generateUrl('provider_dash'));
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
    private function createLoginForm(AdmUser $entity) {
        $form = $this->createForm(new AdmUserType(), $entity, array(
            'action' => $this->generateUrl('adm_dologin'),
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

    private function doAuth(AdmUser $entity) {
        
        $entity->getPassword();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MarcoshoyaMarquejogoBundle:AdmUser')->findOneBy(array(
            'username' => $entity->getUserName(),
            'password' => $entity->getPassword(),
        ));

        if ($user instanceof AdmUser) {
            try {
                $providerKey = 'admin';
                $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

                $this->get('security.context')->setToken($token);

                $session = $this->getRequest()->getSession();
                $session->set('_security_main', serialize($token));
                
                if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
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
