<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Adm;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Adm\DashboardTest;

/**
 * ProviderControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ProviderControllerTest extends DashboardTest
{

    /**
     * It tests all provider functions
     */
    public function testCompleteScenario()
    {
        $this->logIn();

        // List all providers
        $crawler = $this->client->request('GET', '/adm/provider/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Listando fornecedores")')->count());

        // Create a new entry in the database
        $crawler = $this->client->click($crawler->selectLink('Inserir Novo')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_provider[name]' => 'Provider test',
            'marcoshoya_marquejogobundle_provider[description]' => 'Provider description',
            'marcoshoya_marquejogobundle_provider[cnpj]' => '123456789',
            'marcoshoya_marquejogobundle_provider[phone]' => '9876-5432',
            'marcoshoya_marquejogobundle_provider[address]' => 'Provider street',
            'marcoshoya_marquejogobundle_provider[number]' => '0-00',
            'marcoshoya_marquejogobundle_provider[complement]' => '',
            'marcoshoya_marquejogobundle_provider[neighborhood]' => 'Downtown',
            //'marcoshoya_marquejogobundle_provider[statecity][state]'  => '6', // pr
            //'marcoshoya_marquejogobundle_provider[statecity][city]'  => 20531, // cwb
            'marcoshoya_marquejogobundle_provider[username]' => 'provider@email.com',
            'marcoshoya_marquejogobundle_provider[password]' => 'password',
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Provider test")')->count());


        // Edit the entity
        $crawler = $this->client->click($crawler->selectLink('Provider test')->link());

        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_provider[name]' => 'Provider edited',
            'marcoshoya_marquejogobundle_provider[description]' => 'Provider description',
            'marcoshoya_marquejogobundle_provider[cnpj]' => '123456789',
            'marcoshoya_marquejogobundle_provider[phone]' => '9876-5432',
            'marcoshoya_marquejogobundle_provider[address]' => 'Provider street',
            'marcoshoya_marquejogobundle_provider[number]' => '0-00',
            'marcoshoya_marquejogobundle_provider[complement]' => '',
            'marcoshoya_marquejogobundle_provider[neighborhood]' => 'Downtown',
            //'marcoshoya_marquejogobundle_provider[statecity][state]' => '6', // pr
            //'marcoshoya_marquejogobundle_provider[statecity][city]' => '20531', // cwb
            'marcoshoya_marquejogobundle_provider[username]' => 'provider@email.com',
            'marcoshoya_marquejogobundle_provider[password]' => 'password',
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Provider edited")')->count());

        $provider = $this->em
            ->getRepository('MarcoshoyaMarquejogoBundle:Provider')
            ->findOneBy(array(), array('id' => 'DESC'))
        ;

        // Delete the entity
        $this->client->request('GET', '/adm/provider/' . $provider->getId() . '/delete');
        $crawler = $this->client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Provider edited/', $this->client->getResponse()->getContent());
    }

}
