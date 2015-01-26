<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Site;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marcoshoya\MarquejogoBundle\Helper\BundleHelper;
use Marcoshoya\MarquejogoBundle\Form\SearchType;
use Marcoshoya\MarquejogoBundle\Component\Search\SearchDTO;

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
        $searchDTO = new SearchDTO();
        $session = $this->get('session');
        
        if ($session->has(SearchDTO::session)) {
            $object = $session->get(SearchDTO::session);
            $searchDTO = unserialize($object);
        }
        
        $form = $this->createSearchForm($searchDTO);

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
        $search = new SearchDTO();
        
        $form = $this->createSearchForm($search);
        $form->handleRequest($request);

        if ($form->isValid()) {

            // form data
            $data = $form->getData();
            $slug = BundleHelper::sluggable($data['city']);
            $dateTime = $data['date'];
            $hour = $data['hour'];

            $dateTime->modify("+{$hour} hour");
            $autocomplete = $this->get('marcoshoya_marquejogo.service.autocomplete')->getCity($slug);

            // dto
            $search->setDate($dateTime);
            $search->setAutocomplete($autocomplete);
            
            $this->get('session')->set(SearchDTO::session, serialize($search));

            return $this->redirect($this->generateUrl('search_result', array('city' => $slug)));
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
    private function createSearchForm(SearchDTO $searchDTO)
    {
        $data = array(
            'city' => null !== $searchDTO->getAutocomplete() ? $searchDTO->getAutocomplete()->getNameField() : null,
            'date' => null !== $searchDTO->getDate() ? $searchDTO->getDate() : null,
            'hour' => null !== $searchDTO->getDate() ? $searchDTO->getDate()->format('H') : date('H'),
        );

        $form = $this->createForm(new SearchType(), $data, array(
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
    
    /**
     * @Route("/location", name="location_type")
     * @Method({"POST"})
     */
    public function locationtypeAction(Request $request)
    {
        $state = $request->get('data');
        
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MarcoshoyaMarquejogoBundle:LocationCity')->findAllOrderedByName($state);
    
        $html = '';
        foreach ($entities as $locality) {
            $html = $html . sprintf("<option value=\"%d\">%s</option>", $locality->getId(), ucwords($locality->getName()));
        }
    
        return new Response($html);
    }
}
