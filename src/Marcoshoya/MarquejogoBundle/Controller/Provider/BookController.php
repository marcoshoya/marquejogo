<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Provider;

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
 *
 * @Route("/reserva")
 */
class BookController extends Controller
{

    /**
     * @Route("/{id}/visualizar", name="book_show")
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
     * @Route("/{id}/confirmar", name="provider_book_confirm")
     * @ParamConverter("book", class="MarcoshoyaMarquejogoBundle:Book")
     */
    public function confirmAction(Book $entity)
    {
        try {
            
            $em = $this->getDoctrine()->getManager();
            
            $entity->setStatus('booked');
            $em->persist($entity);
            
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Reserva confirmada com sucesso!');
            
        } catch (\Exception $ex) {
            $this->get('logger')->error("confirmAction error: " . $ex->getMessage());
            $this->get('session')->getFlashBag()->add('error', 'Ocorreu um erro ao confirmar a reserva');
        }
        
        return $this->redirect($this->generateUrl('book_show', array('id' => $entity->getId())));
    }
    
    /**
     * @Route("/{id}/cancelar", name="provider_book_cancel")
     * @ParamConverter("book", class="MarcoshoyaMarquejogoBundle:Book")
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
            $this->get('logger')->error("confirmAction error: " . $ex->getMessage());
            $this->get('session')->getFlashBag()->add('error', 'Ocorreu um erro ao cancelar a reserva');
        }
        
        return $this->redirect($this->generateUrl('book_show', array('id' => $entity->getId())));
    }

}
