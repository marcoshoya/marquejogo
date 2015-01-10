<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Provider;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Provider\DashboardTest;

/**
 * ProviderProductControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ProviderProductControllerTest extends DashboardTest
{
    /**
     * It tests all provider product functions
     */
    public function testCompleteScenario()
    {
        $provider = $this->logIn();

        // List all providers
        $crawler = $this->client->request('GET', '/fornecedor/produtos/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Configurar Quadras")')->count());

        // Create a new entry in the database
        $crawler = $this->client->click($crawler->selectLink('Inserir novo')->link());

        // Test form validate
        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_providerproduct[isActive]' => false,
            'marcoshoya_marquejogobundle_providerproduct[capacity]' => 1,
            'marcoshoya_marquejogobundle_providerproduct[name]' => '',
            'marcoshoya_marquejogobundle_providerproduct[type]' => 'soccer',
            'marcoshoya_marquejogobundle_providerproduct[category]' => 'open',
            'marcoshoya_marquejogobundle_providerproduct[provider]' => $provider->getId(),
        ));

        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span:contains("Campo obrigatório")')->count());


        // Fill in the form and submit it
        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_providerproduct[isActive]' => false,
            'marcoshoya_marquejogobundle_providerproduct[capacity]' => 10,
            'marcoshoya_marquejogobundle_providerproduct[name]' => 'product test',
            'marcoshoya_marquejogobundle_providerproduct[type]' => 'soccer',
            'marcoshoya_marquejogobundle_providerproduct[category]' => 'open',
            'marcoshoya_marquejogobundle_providerproduct[provider]' => $provider->getId(),
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("product test")')->count());

        // Edit the entity
        $crawler = $this->client->click($crawler->selectLink('product test')->link());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Configurar Quadras")')->count());

        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_providerproduct[isActive]' => false,
            'marcoshoya_marquejogobundle_providerproduct[capacity]' => 10,
            'marcoshoya_marquejogobundle_providerproduct[name]' => 'product test edited',
            'marcoshoya_marquejogobundle_providerproduct[type]' => 'soccer',
            'marcoshoya_marquejogobundle_providerproduct[category]' => 'open',
            'marcoshoya_marquejogobundle_providerproduct[provider]' => $provider->getId(),
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check data in the list view
        $crawler = $this->client->click($crawler->selectLink('Voltar')->link());
        $this->assertGreaterThan(0, $crawler->filter('td:contains("product test edited")')->count());

        // Delete the entity
        $product = $this->em
            ->getRepository('MarcoshoyaMarquejogoBundle:ProviderProduct')
            ->findOneBy(array('provider' => $provider->getId()), array('id' => 'DESC'))
        ;

        $this->em->remove($product);
        $this->em->flush();

        // Check the entity has been delete on the list
        $crawler = $this->client->request('GET', '/fornecedor/produtos/');
        $this->assertEmpty($crawler->filter('td:contains("product test edited")')->count());
    }

}
