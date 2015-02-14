<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Provider;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Provider\DashboardTest;

/**
 * SecuredControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class SecuredControllerTest extends DashboardTest
{
    /**
     * Tests provider login form
     *
     * GET /fornecedor/login
     * HTTP/1.1 200 OK
     */
    public function testLogin()
    {
        $this->client->request('GET', $this->router->generate('provider_dash'));
        $crawler = $this->client->followRedirect();

        $this->assertTrue($this->client->getContainer()->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Painel do Fornecedor")')->count());

        // validate form
        $form = $crawler->selectButton('Acessar')->form(array(
            'marcoshoya_marquejogobundle_provider[username]' => '',
            'marcoshoya_marquejogobundle_provider[password]' => '',
        ));

        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span:contains("Campo obrigatório")')->count());

        $provider = $this->em
            ->getRepository("MarcoshoyaMarquejogoBundle:Provider")
            ->findOneBy(array(), array(
                'id' => 'DESC'
            ));

        $form = $crawler->selectButton('Acessar')->form(array(
            'marcoshoya_marquejogobundle_provider[username]' => $provider->getUsername(),
            'marcoshoya_marquejogobundle_provider[password]' => $provider->getPassword()
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Últimas reservas")')->count());
    }
    
    /**
     * Tests provider logout
     *
     * GET /fornecedor/logout
     * HTTP/1.1 200 OK
     */
    public function testLogout()
    {
        $this->client->request('GET', $this->router->generate('provider_logout'));
        $crawler = $this->client->followRedirect();
        
        $this->assertTrue($this->client->getContainer()->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Painel do Fornecedor")')->count());
        
    }
}
