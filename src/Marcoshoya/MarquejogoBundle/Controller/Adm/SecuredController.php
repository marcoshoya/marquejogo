<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Adm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
            $service = $this->get('marcoshoya_marquejogo.service.person');
            $user = $service->getUser($data);

            if (null !== $user) {

                return $this->redirect($this->generateUrl('_adm_dash'));
            }
            
            $this->get('session')->getFlashBag()->add('error', 'Usuário não encontrado');
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
    private function createLoginForm(AdmUser $entity)
    {
        $form = $this->createForm(new AdmUserType(), $entity, array(
            'action' => $this->generateUrl('adm_dologin'),
            'method' => 'POST',
        ));

        $form
            ->add('username', 'text', array(
                'required' => true,
                'trim' => true
            ))
            ->add('password', 'password')
            ->remove('email')
            ->remove('isActive')
        ;

        return $form;
    }

    /**
     * @Route("/logout", name="_marquejogo_adm_logout")
     * @Template()
     */
    public function logoutAction()
    {
        $this->get('security.context')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        return $this->redirect($this->generateUrl('_adm_dash'));
    }

}
