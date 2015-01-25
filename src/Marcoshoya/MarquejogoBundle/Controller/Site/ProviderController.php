<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Marcoshoya\MarquejogoBundle\Entity\Provider;

/**
 * ProviderController implements all provider functions on site
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ProviderController extends Controller
{
    /**
     * List all results from search
     *
     * @Route("/quadra{id}", name="provider_show")
     * @ParamConverter("provider", class="MarcoshoyaMarquejogoBundle:Provider")
     */
    public function showAction(Provider $provider)
    {
        //echo $id;
        
        // , requirements={"slug" = "[0-9a-zA-Z\/\-]*"}
        
        $service = $this->get('marcoshoya_marquejogo.service.search');
        $picture = $service->getAllPicture($provider);
        
        return $this->render('MarcoshoyaMarquejogoBundle:Site/Provider:show.html.twig', array(
            'provider' => $provider,
            'pictures' => $picture
        ));  
    }
}