<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Adm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Marcoshoya\MarquejogoBundle\Entity\AdmUser;
use Marcoshoya\MarquejogoBundle\Form\AdmUserType;

class LoginController extends Controller
{
    /**
     * @Route("/", name="_adm_dash")
     * @Template()
     */
    public function dashboardAction()
    {
        
        return array();
    }
    
    /**
     * @Route("/login", name="_marquejogo_adm_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $entity = new AdmUser();
        $form = $this->createLoginForm($entity);
        
        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                
                $auth = $this->doAuth($data);
                
                if ($auth) {
                    
                    return $this->redirect($this->generateUrl('_adm_dash'));
                }
            }
        }
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    
    /**
     * Create a login form
     * 
     * @param AdmUser $entity
     * @return AdmUserType
     */
    private function createLoginForm(AdmUser $entity) 
    {
        $form = $this->createForm(new AdmUserType(), $entity, array(
            'action' => $this->generateUrl('_marquejogo_adm_login'),
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
    
    private function doAuth(AdmUser $entity)
    {
        $entity->getPassword();
                
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MarcoshoyaMarquejogoBundle:AdmUser')->findOneBy(array(
            'username' => $entity->getUserName(),                    
        ));

        if ($user instanceof AdmUser) {
            
            $providerKey = 'admin';
            $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

            $this->container->get('security.context')->setToken($token);

            $session = $this->getRequest()->getSession();
            $session->set('_security_main',  serialize($token));
            
            return true;
        } else {
            
            return false;
        }
    }
    
}