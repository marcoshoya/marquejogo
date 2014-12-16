<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Adm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LoginController extends Controller
{
    /**
     * @Route("/", name="_adm_dash")
     * @Template()
     */
    public function indexAction()
    {
        
    }
    
    /**
     * @Route("/login", name="_marquejogo_adm_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            
            
        }
    }
    
}