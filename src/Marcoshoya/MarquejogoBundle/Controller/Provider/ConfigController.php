<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Form\ProviderType;

/**
 * ConfigController controller.
 *
 * @Route("/configuracao")
 */
class ConfigController extends Controller
{
    /**
     * It configures the provider data
     *
     * @Route("/", name="providerconfig")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->find($user->getId());

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
            'action' => $this->generateUrl('providerconfig_update', array('id' => $entity->getId())),
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
            ->add('city', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('state', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => "Campo obrigatório")),
                ),
            ))
            ->add('isActive', 'hidden')
            ->add('email', 'hidden')
            ->add('password', 'hidden')
        ;

        return $form;
    }

    /**
     * Edits an existing ProviderProduct entity.
     *
     * @Route("/{id}", name="providerconfig_update")
     * @Method("PUT")
     * @Template("MarcoshoyaMarquejogoBundle:Provider/Config:index.html.twig")
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
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Dados atualizados com sucesso.');

            return $this->redirect($this->generateUrl('providerconfig'));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        );
    }
}
