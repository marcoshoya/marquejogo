<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Helper\BundleHelper;
use Marcoshoya\MarquejogoBundle\Form\SearchType;

/**
 * MainController
 * 
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class MainController extends Controller
{

    /**
     * @Template("MarcoshoyaMarquejogoBundle:Site/Main:main.html.twig")
     */
    public function indexAction()
    {
        $form = $this->createSearchForm();

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Do the search
     *
     * @Route("/doSubmit", name="submit_search")
     * @Method("POST")
     * @Template("MarcoshoyaMarquejogoBundle:Site/Main:main.html.twig")
     */
    public function submitAction(Request $request)
    {
        $form = $this->createSearchForm();
        $form->handleRequest($request);
        
        if ($form->isValid()) {

            $data = $form->getData();

            $city = BundleHelper::sluggable($data['city']);

            return $this->redirect($this->generateUrl('search_result', array('city' => $city)));
        }

        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * Creates the search form
     * 
     * @return SearchType
     */
    private function createSearchForm()
    {
        $form = $this->createForm(new SearchType(), null, array(
            'action' => $this->generateUrl('submit_search'),
            'method' => 'POST',
        ));
        
        return $form;
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
