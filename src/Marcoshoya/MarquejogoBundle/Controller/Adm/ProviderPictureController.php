<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Adm;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Entity\ProviderPicture;

/**
 * ProviderPictureController
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 *
 * @Route("/providerpicture")
 */
class ProviderPictureController extends Controller
{
    /**
     * Insert a image
     *
     * @Route("/{id}/picture", name="provider_picture")
     * @Template()
     */
    public function pictureAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provider entity.');
        }

        $providerPicture = new ProviderPicture();
        $providerPicture->setProvider($entity);
        $providerPicture->setIsActive(false);

        $form = $this->createPictureForm($providerPicture);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Save
     * 
     * @Route("/{id}", name="picture_save")
     * @Method("POST")
     * @Template("MarcoshoyaMarquejogoBundle:Adm/ProviderPicture:picture.html.twig")
     */
    public function picturesaveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provider entity.');
        }

        $providerPicture = new ProviderPicture();
        $providerPicture->setProvider($entity);
        $providerPicture->setIsActive(false);

        $form = $this->createPictureForm($providerPicture);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($providerPicture);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Imagem inserido com sucesso.');

            return $this->redirect($this->generateUrl("provider_edit", array("id" => $id)));
        }
        
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates form
     * 
     * @param ProviderPicture $providerPicture
     * @return Form
     */
    protected function createPictureForm(ProviderPicture $providerPicture)
    {
        $form = $this->createFormBuilder($providerPicture, array(
                'action' => $this->generateUrl('provider_create'),
                'method' => 'POST',
            ))
            ->add('name')
            ->add('file')
            ->getForm()
        ;

        return $form;
    }

    /**
     * Appendix a Content entity.
     *
     * @Route("/picture/{id}/delete", name="picture_delete")
     * @Template()
     */
    public function picturedeleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:ProviderPicture')->find($id);
        $provider = $entity->getProvider();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ContentAppendix entity.');
        }

        try {
            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Imagem excluída com sucesso.');
        } catch (\Exception $e) {
            $this->get('logger')->err("Error: " . $e->getMessage());
            $this->get('session')->getFlashBag()->add('error', 'Ocorreu um erro ao excluir a imagem.');
        }

        return $this->redirect($this->generateUrl("provider_edit", array("id" => $provider->getId())));
    }

}
