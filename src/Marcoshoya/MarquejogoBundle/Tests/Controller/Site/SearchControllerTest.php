<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Site;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Site\MainControllerTest;

/**
 * SearchControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class SearchControllerTest extends MainControllerTest
{
    /**
     * Test search
     */
    public function testSearch()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Cidade")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Data")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Hora")')->count());
        
        // Test form validate
        $form = $crawler->selectButton('Pesquisar')->form(array(
            'marcoshoya_marquejogobundle_search[city]' => '',
            'marcoshoya_marquejogobundle_search[date]' => '1111',
            'marcoshoya_marquejogobundle_search[hour]' => date('H'),
        ));
        
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span:contains("Campo obrigatório")')->count());
        // invalid date
        $this->assertGreaterThan(0, $crawler->filter('span:contains("This value is not valid.")')->count());
        
        // Test form validate
        $form = $crawler->selectButton('Pesquisar')->form(array(
            'marcoshoya_marquejogobundle_search[city]' => 'Curitiba, Paraná',
            'marcoshoya_marquejogobundle_search[date]' => date('d-m-Y'),
            'marcoshoya_marquejogobundle_search[hour]' => date('H'),
        ));
        
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Resultados encontrados")')->count());
        
    }
}
