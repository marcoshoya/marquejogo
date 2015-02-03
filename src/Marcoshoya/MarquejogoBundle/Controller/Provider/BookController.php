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
}
