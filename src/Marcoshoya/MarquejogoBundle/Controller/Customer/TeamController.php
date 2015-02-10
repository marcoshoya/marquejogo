<?php

namespace Marcoshoya\MarquejogoBundle\Controller\Customer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Marcoshoya\MarquejogoBundle\Entity\Team;
use Marcoshoya\MarquejogoBundle\Form\TeamType;

/**
 * Team controller.
 *
 * @Route("/time")
 */
class TeamController extends Controller
{

    /**
     * Verify if user logged is team owner
     * 
     * @param Team $entity
     * @return boolean
     */
    private function isOwner(Team $entity)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        return ($entity->getOwner()->getId() !== $user->getId()) ? false : true;
    }
    
    /**
     * Lists all Team entities.
     *
     * @Route("/listar", name="customer_team_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $customer = $em->getRepository('MarcoshoyaMarquejogoBundle:Customer')->find($user->getId());


        $entities = $em->getRepository('MarcoshoyaMarquejogoBundle:Team')->findBy(array(
            'owner' => $customer
        ));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Team entity.
     *
     * @Route("/novo", name="customer_team_create")
     * @Method("POST")
     * @Template("MarcoshoyaMarquejogoBundle:Customer/Team:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Team();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Time inserido com sucesso!');

            return $this->redirect($this->generateUrl('customer_team_list'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Team entity.
     *
     * @param Team $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Team $entity)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $entity->setOwner($user);

        $form = $this->createForm(new TeamType(), $entity, array(
            'action' => $this->generateUrl('customer_team_create'),
            'method' => 'POST',
            'validation_groups' => array('register')
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Team entity.
     *
     * @Route("/novo", name="customer_team_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Team();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Team entity.
     *
     * @Route("/{id}/editar", name="customer_team_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Team')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }

        // verifies if user is the owner
        if (!$this->isOwner($entity)) {
            $this->get('session')->getFlashBag()->add('error', 'Não é possível acessar esse time.');

            return $this->redirect($this->generateUrl('customer_team_list'));
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Team entity.
     *
     * @param Team $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Team $entity)
    {
        $form = $this->createForm(new TeamType(), $entity, array(
            'action' => $this->generateUrl('customer_team_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'validation_groups' => array('register')
        ));

        return $form;
    }

    /**
     * Edits an existing Team entity.
     *
     * @Route("/{id}/editar", name="customer_team_update")
     * @Method("PUT")
     * @Template("MarcoshoyaMarquejogoBundle:Customer/Team:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Team')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Time atualizado com sucesso!');

            return $this->redirect($this->generateUrl('customer_team_list'));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        );
    }

    /**
     * Lists all Team players
     *
     * @Route("/{id}/jogadores", name="customer_team_player")
     * @ParamConverter("entity", class="MarcoshoyaMarquejogoBundle:Team")
     * @Method("GET")
     * @Template()
     */
    public function playerAction(Team $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $owner = $this->isOwner($entity);

        $players = $em->getRepository('MarcoshoyaMarquejogoBundle:TeamPlayer')->findBy(array(
            'team' => $entity
        ));

        return array(
            'team' => $entity,
            'players' => $players,
            'owner' => $owner,
            'user' => $user
        );
    }

    /**
     * Delete player from team
     *
     * @Route("/{id}/jogador/{player}/excluir", name="customer_team_player_delete")
     * @Method("GET")
     * @Template()
     */
    public function playerdeleteAction($id, $player)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcoshoyaMarquejogoBundle:Team')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }

        // verifies if user is the owner
        if (!$this->isOwner($entity)) {
            $this->get('session')->getFlashBag()->add('error', 'Não é possível acessar esse time.');

            return $this->redirect($this->generateUrl('customer_team_list'));
        }

        try {

            $teamPlayer = $em->getRepository('MarcoshoyaMarquejogoBundle:TeamPlayer')->findOneBy(array(
                'team' => $entity,
                'player' => $player
            ));
            
            $em->remove($teamPlayer);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Jogador excluido com sucesso!');
            
        } catch (\Exception $ex) {
            $this->get('logger')->error('playerdeleteAction error: ' . $ex->getMessage());
            $this->get('session')->getFlashBag()->add('error', 'Ocorreu um erro ao excluir o jogador');
        }

        return $this->redirect($this->generateUrl('customer_team_player', array(
            'id' => $id
        )));
    }
    
    /**
     * Invite a friend to team
     *
     * @Route("/{id}/convidar", name="customer_team_player_invite")
     * @ParamConverter("entity", class="MarcoshoyaMarquejogoBundle:Team")
     * @Template()
     */
    public function playerinviteAction(Team $entity)
    {
        return array(
            'entity' => $entity,
        );
    }

}
