<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Customer\DashboardTest;

/**
 * CustomerControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class CustomerControllerTest extends DashboardTest
{
    /**
     * Tests customer register form
     *
     * GET /cliente/cadastrar/
     * HTTP/1.1 200 OK
     */
    public function testRegister()
    {
        $crawler = $this->client->request('GET', $this->router->generate('customer_new'));

        $this->assertTrue($this->client->getContainer()->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Novo cadastro")')->count());

        // validate form
        $form = $crawler->selectButton('Cadastrar')->form(array(
            'marcoshoya_marquejogobundle_customer[name]' => '',
            'marcoshoya_marquejogobundle_customer[cpf]' => '',
            'marcoshoya_marquejogobundle_customer[phone]' => '',
            'marcoshoya_marquejogobundle_customer[username]' => '',
            'marcoshoya_marquejogobundle_customer[password]' => '',
        ));

        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span:contains("Campo obrigatório")')->count());


        // persist
        $form = $crawler->selectButton('Cadastrar')->form(array(
            'marcoshoya_marquejogobundle_customer[name]' => 'Register customer',
            'marcoshoya_marquejogobundle_customer[cpf]' => '123.456.789-10',
            'marcoshoya_marquejogobundle_customer[phone]' => '41 9999-8888',
            'marcoshoya_marquejogobundle_customer[username]' => 'registertest@marquejogo.com',
            'marcoshoya_marquejogobundle_customer[password]' => 'password',
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Bem vindo Register customer")')->count());
    }

    /**
     * Tests customer edit form
     *
     * GET /cliente/dados/editar/
     * HTTP/1.1 200 OK
     */
    public function testEditprofile()
    {
        $customer = $this->em->getRepository('MarcoshoyaMarquejogoBundle:Customer')
            ->findOneBy(array('username' => 'registertest@marquejogo.com'));

        if (!$customer) {
            $this->fail('testEditprofile: customer not found.');
        }

        $this->logIn($customer);

        $crawler = $this->client->request('GET', $this->router->generate('customer_edit'));
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Meus dados")')->count());
        
        // validate form
        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_customer[username]' => $customer->getUsername(),
            'marcoshoya_marquejogobundle_customer[password]' => $customer->getPassword(),
            'marcoshoya_marquejogobundle_customer[name]' => $customer->getName(),
            'marcoshoya_marquejogobundle_customer[cpf]' => $customer->getCpf(),
            'marcoshoya_marquejogobundle_customer[gender]' => $customer->getGender(),
            'marcoshoya_marquejogobundle_customer[position]' => $customer->getPosition(),
            'marcoshoya_marquejogobundle_customer[birthday]' => $customer->getBirthday(),
            'marcoshoya_marquejogobundle_customer[phone]' => $customer->getPhone(),
            'marcoshoya_marquejogobundle_customer[address]' => $customer->getAddress(),
            'marcoshoya_marquejogobundle_customer[number]' => $customer->getNumber(),
            'marcoshoya_marquejogobundle_customer[neighborhood]' => $customer->getNeighborhood(),
            'marcoshoya_marquejogobundle_customer[city]' => $customer->getCity(),
            'marcoshoya_marquejogobundle_customer[state]' => $customer->getState(),
            
        ));

        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span:contains("Campo obrigatório")')->count());
        
        // validate form
        $form = $crawler->selectButton('Salvar')->form(array(
            'marcoshoya_marquejogobundle_customer[username]' => $customer->getUsername(),
            'marcoshoya_marquejogobundle_customer[password]' => $customer->getPassword(),
            'marcoshoya_marquejogobundle_customer[name]' => $customer->getName(),
            'marcoshoya_marquejogobundle_customer[cpf]' => $customer->getCpf(),
            'marcoshoya_marquejogobundle_customer[gender]' => 'male',
            'marcoshoya_marquejogobundle_customer[position]' => 'defender',
            'marcoshoya_marquejogobundle_customer[birthday]' => '15/11/1989',
            'marcoshoya_marquejogobundle_customer[phone]' => $customer->getPhone(),
            'marcoshoya_marquejogobundle_customer[address]' => '1st Street',
            'marcoshoya_marquejogobundle_customer[number]' => '10-50',
            'marcoshoya_marquejogobundle_customer[neighborhood]' => 'Oldtown',
            'marcoshoya_marquejogobundle_customer[city]' => 'Curitiba',
            'marcoshoya_marquejogobundle_customer[state]' => 6,
            
        ));
        
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("sucesso")')->count());
        
        $this->em->remove($customer);
        $this->em->flush();
        
    }
}
