<?php

namespace Marcoshoya\MarquejogoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MarcoshoyaMarquejogoBundle:Default:index.html.twig', array('name' => $name));
    }
}
