<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Customer;

#use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/reserva/{id}/convidar", name="customer_book_invite")
     * @ParamConverter("book", class="MarcoshoyaMarquejogoBundle:Book")
     * @Method("GET")
     * @Template()
     */
    public function inviteAction(Book $entity)
    {
        return array(
            'entity' => $entity
        );
    }

}
