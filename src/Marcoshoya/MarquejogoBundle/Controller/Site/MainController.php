<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('MarcoshoyaMarquejogoBundle:Site/Main:main.html.twig');
    }
}
