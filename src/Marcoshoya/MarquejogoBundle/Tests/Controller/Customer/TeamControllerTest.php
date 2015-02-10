<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Customer;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Customer\DashboardTest;
use Marcoshoya\MarquejogoBundle\Entity\Customer;

/**
 * TeamControllerTest
 * 
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class TeamControllerTest extends DashboardTest
{

    /**
     * Tests customer team functions
     * 
     * GET /cliente/time
     * HTTP/1.1 200 OK
     */
    public function testCompleteScenario()
    {
        // creates a new customer to test
        $customer = new Customer();
        $customer->setName('Team test customer');
        $customer->setCpf('123.456.789-10');
        $customer->setPhone('41 9999-8888');
        $customer->setUsername('teamtest@marquejogo.com');
        $customer->setPassword('password');
        $this->em->persist($customer);
        $this->em->flush();

        $this->logIn($customer);

        // Create a new entry in the database
        $crawler = $this->client->request('GET', $this->router->generate('customer_team_list'));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Meus Times")')->count());

        $crawler = $this->client->click($crawler->selectLink('Inserir novo')->link());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Inserir novo time")')->count());
        
        

        // Fill in the form and submit it (form fail)
        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_team[owner]' => $customer->getId(),
            'marcoshoya_marquejogobundle_team[name]' => '',
        ));

        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span:contains("Campo obrigatório")')->count());

        // Fill in the form and submit it (form valid)
        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_team[name]' => 'Test Team',
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check data in the list view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test Team")')->count());

        // Edit the entity
        $crawler = $this->client->click($crawler->selectLink('Editar')->link());

        // Fill in the form and submit it (form fail)
        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_team[name]' => '',
        ));

        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span:contains("Campo obrigatório")')->count());

        // Fill in the form and submit it (form valid)
        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_team[name]' => 'Test Team edited',
        ));
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();


        // Delete the entity
        $team = $this->em->getRepository("MarcoshoyaMarquejogoBundle:Team")->findOneBy(array(
            'owner' => $customer
        ));

        $this->em->remove($team);
        $this->em->flush();

        // Check the entity has been delete on the list
        $crawler = $this->client->request('GET', $this->router->generate('customer_team_list'));
        $this->assertNotRegExp('/Test Team edited/', $this->client->getResponse()->getContent());

        $this->em->remove($customer);
        $this->em->flush();
        
    }

}
