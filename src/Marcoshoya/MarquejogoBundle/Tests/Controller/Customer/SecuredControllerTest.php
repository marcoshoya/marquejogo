<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Customer\DashboardTest;

/**
 * SecuredControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class SecuredControllerTest extends DashboardTest
{
    /**
     * Tests customer login form
     * 
     * GET /cliente/login
     * HTTP/1.1 200 OK
     */
    public function testLogin()
    {
        $crawler = $this->client->request('GET', '/cliente/');
        $crawler = $this->client->followRedirect();

        $this->assertTrue($this->client->getContainer()->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Acesse sua conta")')->count());
        
        // validate form
        $form = $crawler->selectButton('Continuar')->form(array(
            'marcoshoya_marquejogobundle_customer[username]' => '',
            'marcoshoya_marquejogobundle_customer[password]' => '',
        ));
        
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span:contains("Campo obrigatório")')->count());

        $customer = $this->em
            ->getRepository("MarcoshoyaMarquejogoBundle:Customer")
            ->findOneBy(array(), array(
                'id' => 'DESC'
            ));

        $form = $crawler->selectButton('Continuar')->form(array(
            'marcoshoya_marquejogobundle_customer[username]' => $customer->getUsername(),
            'marcoshoya_marquejogobundle_customer[password]' => $customer->getPassword()
        ));
        
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Bem vindo")')->count());
    }
    
    /**
     * Tests customer register form
     * 
     * POST /cliente/doRegister
     * HTTP/1.1 200 OK
     */
    public function testRegister()
    {
        $crawler = $this->client->request('GET', '/cliente/');
        $crawler = $this->client->followRedirect();

        $this->assertTrue($this->client->getContainer()->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Acesse sua conta")')->count());
        
        // validate form
        $form = $crawler->selectButton('Cadastrar')->form(array(
            'marcoshoya_marquejogobundle_customer[username]' => '',
        ));
        
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span:contains("Campo obrigatório")')->count());
        
        $form = $crawler->selectButton('Cadastrar')->form(array(
            'marcoshoya_marquejogobundle_customer[username]' => 'registertest@marquejogo.com',
        ));
        
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Novo cadastro")')->count());
    }
}
