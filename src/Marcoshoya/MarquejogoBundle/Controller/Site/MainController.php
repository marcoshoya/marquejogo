<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Form\SearchType;

class MainController extends Controller
{
    /**
     * @Template("MarcoshoyaMarquejogoBundle:Site/Main:main.html.twig")
     */
    public function indexAction()
    {
        $form = $this->createForm(new SearchType(), null, array(
            'action' => $this->generateUrl('do_search'),
            'method' => 'POST',
        ));
        
        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * Header action
     *
     * @return string
     */
    public function headerAction()
    {
        return $this->render('MarcoshoyaMarquejogoBundle:Site/Main:header.html.twig');
    }
    
    /**
     * Footer action
     *
     * @return string
     */
    public function footerAction()
    {
        return $this->render('MarcoshoyaMarquejogoBundle:Site/Main:footer.html.twig');
    }
}
