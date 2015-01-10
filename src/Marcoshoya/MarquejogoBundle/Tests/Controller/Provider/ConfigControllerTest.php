<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Provider;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Provider\DashboardTest;

/**
 * ProviderProductControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ConfigControllerTest extends DashboardTest
{
    /**
     * It tests configuration page
     */
    public function testCompleteScenario()
    {
        $provider = $this->logIn();
        
        // Test the page
        $crawler = $this->client->request('GET', '/fornecedor/configuracao/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Configuração")')->count());
        
        // Test form validate
        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_provider[name]'  => '',
            'marcoshoya_marquejogobundle_provider[description]'  => 'Provider description',
            'marcoshoya_marquejogobundle_provider[cnpj]'  => '123456789',
            'marcoshoya_marquejogobundle_provider[phone]'  => '9876-5432',
            'marcoshoya_marquejogobundle_provider[address]'  => 'Provider street',
            'marcoshoya_marquejogobundle_provider[number]'  => '0-00',
            'marcoshoya_marquejogobundle_provider[complement]'  => '',
            'marcoshoya_marquejogobundle_provider[neighborhood]'  => 'Downtown',
            'marcoshoya_marquejogobundle_provider[city]'  => 'Curitiba',
            'marcoshoya_marquejogobundle_provider[state]'  => 'Parana',
        ));
        
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span:contains("Campo obrigatório")')->count());
        
        // Fill in the form and submit it
        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_provider[name]'  => 'Provider config test',
            'marcoshoya_marquejogobundle_provider[description]'  => 'Provider description',
            'marcoshoya_marquejogobundle_provider[cnpj]'  => '123456789',
            'marcoshoya_marquejogobundle_provider[phone]'  => '9876-5432',
            'marcoshoya_marquejogobundle_provider[address]'  => 'Provider street',
            'marcoshoya_marquejogobundle_provider[number]'  => '0-00',
            'marcoshoya_marquejogobundle_provider[complement]'  => '',
            'marcoshoya_marquejogobundle_provider[neighborhood]'  => 'Downtown',
            'marcoshoya_marquejogobundle_provider[city]'  => 'Curitiba',
            'marcoshoya_marquejogobundle_provider[state]'  => 'Parana',
        ));
        
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Dados atualizados com sucesso")')->count());

    }
}

