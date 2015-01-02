<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Adm;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Adm\DashboardTest;

/**
 * AdmUserControllerTest
 * 
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class AdmUserControllerTest extends DashboardTest
{
    /**
     * It tests all users functions
     */
    public function testCompleteScenario()
    {
        $this->logIn();

        // List all users
        $crawler = $this->client->request('GET', '/adm/admuser/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Listando usuários")')->count());

        // Create a new entry in the database
        $crawler = $this->client->click($crawler->selectLink('Inserir Novo')->link());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Inserir usuário")')->count());
        

        // Fill in the form and submit it
        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_admuser[username]'  => 'User test',
            'marcoshoya_marquejogobundle_admuser[email]'  => 'user@test.com',
            'marcoshoya_marquejogobundle_admuser[password]'  => 'password',
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("User test")')->count());

        // Edit the entity
        $crawler = $this->client->click($crawler->selectLink('User test')->link());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Editando usuário")')->count());

        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_admuser[username]'  => 'User edited',
            'marcoshoya_marquejogobundle_admuser[email]'  => 'user@test.com',
            'marcoshoya_marquejogobundle_admuser[password]'  => 'password',
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("User edited")')->count());

        // Delete the entity
        $provider = $this->em
            ->getRepository('MarcoshoyaMarquejogoBundle:AdmUser')
            ->findOneBy(array(), array('id' => 'DESC'))
        ;        

        // Delete the entity
        $this->client->request('GET', '/adm/admuser/'.$provider->getId().'/delete');
        $crawler = $this->client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Provider edited/', $this->client->getResponse()->getContent());
    }

   
}
