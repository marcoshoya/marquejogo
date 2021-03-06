<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Customer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Marcoshoya\MarquejogoBundle\Entity\Book;

/**
 * BookController
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BookController extends Controller
{
    /**
     * @Route("/reserva/listar", name="customer_book_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('MarcoshoyaMarquejogoBundle:Book')->findBy(
            array('customer' => $user), 
            array('id' => 'DESC')
        );

        return array('entities' => $entities);
    }

    /**
     * @Route("/reserva/{id}/visualizar", name="customer_book_show")
     * @ParamConverter("book", class="MarcoshoyaMarquejogoBundle:Book")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Book $entity)
    {
        return array(
            'entity' => $entity
        );
    }
    
    /**
     * @Route("/reserva/{id}/cancelar", name="customer_book_cancel")
     * @ParamConverter("book", class="MarcoshoyaMarquejogoBundle:Book")
     * @Template()
     */
    public function cancelAction(Book $entity)
    {
        try {
            
            $em = $this->getDoctrine()->getManager();
            
            $entity->setStatus('cancelled');
            $em->persist($entity);
            
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Reserva cancelada com sucesso!');
            
        } catch (\Exception $ex) {
            $this->get('logger')->error("cancelAction error: " . $ex->getMessage());
            $this->get('session')->getFlashBag()->add('error', 'Ocorreu um erro ao cancelar a reserva');
        }
        
        return $this->redirect($this->generateUrl('customer_book_show', array('id' => $entity->getId())));
    }
    
    /**
     * @Route("/reserva/{id}/convidar", name="customer_book_invite")
     * @ParamConverter("book", class="MarcoshoyaMarquejogoBundle:Book")
     * @Template()
     */
    public function inviteAction(Book $entity, Request $request)
    {
        $data = array();
        
        $form = $this->createSearchFriendForm($data, $entity);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            // @TODO: send email
            
            $this->get('session')->getFlashBag()->add('success', 'E-mail enviado com sucesso!');
            
            return $this->redirect($this->generateUrl('customer_book_invite', array('id' => $entity->getId())));
        }
        
        return array(
            'results' => null,
            'form' => $form->createView(), 
            'entity' => $entity
        );
    }
    
    /**
     * Creates the form
     * 
     * @param array $data
     * @param Team $entity
     * 
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSearchFriendForm($data, Book $entity)
    {
        $form = $this->createFormBuilder($data, array(
                'action' => $this->generateUrl('customer_book_invite', array('id' => $entity->getId())),
                'method' => 'POST',
            ))
            ->add('email', 'email', array(
                'constraints' => array(
                    new Assert\NotBlank(array('message' => 'Campo obrigátorio')),
                    new Assert\Email(array('message' => 'Formato do e-mail inválido')),
            )))
            ->add('enviar', 'submit')
            ->getForm()
        ;

        return $form;
    }

}
