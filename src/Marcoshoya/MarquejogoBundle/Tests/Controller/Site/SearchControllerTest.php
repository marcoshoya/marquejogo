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
     * Tests main page
     *
     * GET /
     * HTTP/1.1 200 OK
     */
    public function testSearch()
    {
        $crawler = $this->client->request('GET', $this->router->generate('marquejogo_homepage'));

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

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Resultados encontrados")')->count());
    }

    /**
     * Tests a invalid search
     *
     * GET /busca/invalid
     * HTTP/1.1 200 OK
     */
    public function testInvalidResult()
    {
        $crawler = $this->client->request('GET', $this->router->generate('marquejogo_homepage'));

        // Test form validate
        $form = $crawler->selectButton('Pesquisar')->form(array(
            'marcoshoya_marquejogobundle_search[city]' => 'invalid',
            'marcoshoya_marquejogobundle_search[date]' => date('d-m-Y'),
            'marcoshoya_marquejogobundle_search[hour]' => date('H'),
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Nenhum Resultado Encontrado")')->count());
    }

    /**
     * Tests a re-search using sidebar from result page
     *
     * GET /busca/invalid-result
     * HTTP/1.1 200 OK
     */
    public function testSidebar()
    {
        $crawler = $this->client->request('GET', $this->router->generate('search_result', array(
                'city' => 'invalid-result'
        )));

        // Test form validate
        $form = $crawler->selectButton('Pesquisar')->form(array(
            'marcoshoya_marquejogobundle_search[city]' => '',
            'marcoshoya_marquejogobundle_search[date]' => '1111',
            'marcoshoya_marquejogobundle_search[hour]' => date('H'),
        ));

        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span:contains("Campo obrigatório")')->count());

        // Test form validate
        $form = $crawler->selectButton('Pesquisar')->form(array(
            'marcoshoya_marquejogobundle_search[city]' => 'Curitiba, Paraná',
            'marcoshoya_marquejogobundle_search[date]' => date('d-m-Y'),
            'marcoshoya_marquejogobundle_search[hour]' => date('H'),
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Resultados encontrados")')->count());
    }

}
