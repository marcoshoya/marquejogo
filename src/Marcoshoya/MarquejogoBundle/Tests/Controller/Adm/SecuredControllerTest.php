<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Adm;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Adm\DashboardTest;

/**
 * SecuredControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class SecuredControllerTest extends DashboardTest
{
    /**
     * Tests adm login form
     *
     * GET /adm/login
     * HTTP/1.1 200 OK
     */
    public function testLogin()
    {
        $this->client->request('GET', $this->router->generate('_adm_dash'));
        $crawler = $this->client->followRedirect();

        $this->assertTrue($this->client->getContainer()->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Administrador")')->count());

        // validate form
        $form = $crawler->selectButton('Acessar')->form(array(
            'marcoshoya_marquejogobundle_admuser[username]' => '',
            'marcoshoya_marquejogobundle_admuser[password]' => '',
        ));

        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span:contains("Campo obrigatório")')->count());

        $user = $this->em
            ->getRepository("MarcoshoyaMarquejogoBundle:AdmUser")
            ->findOneBy(array(), array(
                'id' => 'DESC'
            ));

        $form = $crawler->selectButton('Acessar')->form(array(
            'marcoshoya_marquejogobundle_admuser[username]' => $user->getUsername(),
            'marcoshoya_marquejogobundle_admuser[password]' => $user->getPassword()
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Administrador")')->count());
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
