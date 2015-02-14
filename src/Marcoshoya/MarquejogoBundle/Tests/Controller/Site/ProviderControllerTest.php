<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Site;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Site\MainControllerTest;

/**
 * ProviderControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ProviderControllerTest extends MainControllerTest
{

    /**
     * @var \Marcoshoya\MarquejogoBundle\Entity\Provider
     */
    protected $provider;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->provider = $this->em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->findOneBy(
            array(), array('id' => 'DESC')
        );
    }

    /**
     * Tests the provider page
     *
     * GET /quadra{id}
     * HTTP/1.1 200 OK
     */
    public function testIndex()
    {
        $crawler = $this->client->request('GET', $this->router->generate('provider_show', array(
                'id' => $this->provider->getId(),
        )));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("' . $this->provider->getName() . '")')->count());

        // search avail form (invalid)
        $form = $crawler->selectButton('Consultar')->form(array(
            'marcoshoya_marquejogobundle_avail[provider]' => $this->provider->getId(),
            'marcoshoya_marquejogobundle_avail[date]' => '',
            'marcoshoya_marquejogobundle_avail[hour]' => date('H'),
        ));

        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Campo obrigatório")')->count());

        // search avail form (valid)
        $form = $crawler->selectButton('Consultar')->form(array(
            'marcoshoya_marquejogobundle_avail[provider]' => $this->provider->getId(),
            'marcoshoya_marquejogobundle_avail[date]' => date('d-m-Y'),
            'marcoshoya_marquejogobundle_avail[hour]' => date('H'),
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Tests the provider page
     *
     * POST /quadra{id}/doSearch
     * HTTP/1.1 200 OK
     */
    public function testSidebar()
    {
        $crawler = $this->client->request('GET', $this->router->generate('provider_show', array(
                'id' => $this->provider->getId(),
        )));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // test search (invalid)
        $form = $crawler->selectButton('Pesquisar')->form(array(
            'marcoshoya_marquejogobundle_search[city]' => '',
            'marcoshoya_marquejogobundle_search[date]' => '1111',
            'marcoshoya_marquejogobundle_search[hour]' => date('H'),
        ));

        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Campo obrigatório")')->count());

        // test search (valid)
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
