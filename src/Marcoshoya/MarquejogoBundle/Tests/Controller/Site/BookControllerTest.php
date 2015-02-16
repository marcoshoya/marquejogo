<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Site;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Site\MainControllerTest;

/**
 * BookControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BookControllerTest extends MainControllerTest
{

    /**
     * @var \Marcoshoya\MarquejogoBundle\Entity\Provider
     */
    protected $provider;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->provider = $this->em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->findOneBy(
            array(), array('id' => 'ASC')
        );
    }

    /**
     * Clear book data
     *
     * @param boolean $flag If true, removes customer data
     */
    protected function clearData($flag = false)
    {
        $user = $this->em->getRepository('MarcoshoyaMarquejogoBundle:Customer')->findOneBy(array(
            'username' => 'customerbook@marquejogo.com'
        ));

        $team = $this->em->getRepository('MarcoshoyaMarquejogoBundle:Team')->findOneBy(array(
            'owner' => $user
        ));

        $book = $this->em->getRepository('MarcoshoyaMarquejogoBundle:Book')->findOneBy(array(
            'customer' => $user
        ));

        $bookItem = $this->em->getRepository('MarcoshoyaMarquejogoBundle:BookItem')->findOneBy(array(
            'book' => $book
        ));

        $this->em->remove($bookItem);
        $this->em->remove($book);

        if ($flag) {
            $this->em->remove($team);
            $this->em->remove($user);
        }

        $this->em->flush();
    }

    /**
     * Tests book process
     *
     * GET /quadra{id}/informacao
     * HTTP/1.1 200 OK
     */
    public function testBook()
    {
        // starts on provider page
        $crawler = $this->client->request('GET', $this->router->generate('provider_show', array(
                'id' => $this->provider->getId(),
        )));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Refaça sua busca: nenhuma quadra encontrada!")')->count());

        // date to reference
        $date = new \DateTime(date('Y-m-d 20:00:00'));
        $date->modify('+1 day');

        $scheduleItem = $this->configureSchedule($this->provider, $date);

        // search avail form
        $formAvail = $crawler->selectButton('Consultar')->form(array(
            'marcoshoya_marquejogobundle_avail[provider]' => $this->provider->getId(),
            'marcoshoya_marquejogobundle_avail[date]' => $date->format('d-m-Y'),
            'marcoshoya_marquejogobundle_avail[hour]' => $date->format('H'),
        ));

        $this->client->submit($formAvail);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Tipo de Quadra")')->count(), 'Fail on Tipo de Quadra');

        // initial book form (fail)
        $form = $crawler->selectButton('Reservar')->form(array(
            'marcoshoya_marquejogobundle_schedule[provider]' => $this->provider->getId(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][date]' => $scheduleItem->getDate()->format('Y-m-d H:i:s'),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][available]' => $scheduleItem->getAvailable(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][providerProduct]' => $scheduleItem->getProviderProduct()->getId(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][schedule]' => $scheduleItem->getSchedule()->getId(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][price]' => $scheduleItem->getPrice(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][alocated]' => '',
        ));

        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Selecione ao menos uma quadra para reservar")')->count(), 'Fail on ');

        // initial book form (valid)
        $form = $crawler->selectButton('Reservar')->form(array(
            'marcoshoya_marquejogobundle_schedule[provider]' => $this->provider->getId(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][date]' => $scheduleItem->getDate()->format('Y-m-d H:i:s'),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][available]' => $scheduleItem->getAvailable(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][providerProduct]' => $scheduleItem->getProviderProduct()->getId(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][schedule]' => $scheduleItem->getSchedule()->getId(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][price]' => $scheduleItem->getPrice(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][alocated]' => 01,
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Insira seus dados")')->count());

        // information form (invalid)
        $formBook = $crawler->selectButton('Continuar')->form(array(
            'marcoshoya_marquejogobundle_customer[name]' => 'Customer Book',
            'marcoshoya_marquejogobundle_customer[username]' => 'customerbook@marquejogo.com',
            'marcoshoya_marquejogobundle_customer[phone]' => '9999-8877',
            'marcoshoya_marquejogobundle_customer[password]' => 'password',
            'marcoshoya_marquejogobundle_customer[team][0][name]' => ''
        ));

        $crawler = $this->client->submit($formBook);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Campo obrigatório")')->count());

        // information form (valid)
        $formBook = $crawler->selectButton('Continuar')->form(array(
            'marcoshoya_marquejogobundle_customer[name]' => 'Customer Book',
            'marcoshoya_marquejogobundle_customer[username]' => 'customerbook@marquejogo.com',
            'marcoshoya_marquejogobundle_customer[phone]' => '9999-8877',
            'marcoshoya_marquejogobundle_customer[password]' => 'password',
            'marcoshoya_marquejogobundle_customer[team][0][name]' => 'Book Team'
        ));

        $this->client->submit($formBook);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Reserva finalizada!")')->count());

        $this->clearData();
    }

    /**
     * Tests book process using authentication
     *
     * GET /quadra{id}/informacao
     * HTTP/1.1 200 OK
     */
    public function testBookAuth()
    {
        // starts on provider page
        $crawler = $this->client->request('GET', $this->router->generate('provider_show', array(
                'id' => $this->provider->getId(),
        )));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // date to reference
        $date = new \DateTime(date('Y-m-d 20:00:00'));
        $date->modify('+1 day');

        $scheduleItem = $this->configureSchedule($this->provider, $date);

        // search avail form
        $formAvail = $crawler->selectButton('Consultar')->form(array(
            'marcoshoya_marquejogobundle_avail[provider]' => $this->provider->getId(),
            'marcoshoya_marquejogobundle_avail[date]' => $date->format('d-m-Y'),
            'marcoshoya_marquejogobundle_avail[hour]' => $date->format('H'),
        ));

        $this->client->submit($formAvail);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Tipo de Quadra")')->count(), 'Fail on Tipo de Quadra');

        // initial book form (valid)
        $form = $crawler->selectButton('Reservar')->form(array(
            'marcoshoya_marquejogobundle_schedule[provider]' => $this->provider->getId(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][date]' => $scheduleItem->getDate()->format('Y-m-d H:i:s'),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][available]' => $scheduleItem->getAvailable(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][providerProduct]' => $scheduleItem->getProviderProduct()->getId(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][schedule]' => $scheduleItem->getSchedule()->getId(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][price]' => $scheduleItem->getPrice(),
            'marcoshoya_marquejogobundle_schedule[scheduleItem][0][alocated]' => 01,
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Insira seus dados")')->count());

        // authentication form (invalid)
        $formBook = $crawler->selectButton('Entrar')->form(array(
            'marcoshoya_marquejogobundle_customer[username]' => 'customerbook@marquejogo.com',
            'marcoshoya_marquejogobundle_customer[password]' => '',
        ));

        $crawler = $this->client->submit($formBook);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Campo obrigatório")')->count());

        // authentication form (invalid)
        $formBook = $crawler->selectButton('Entrar')->form(array(
            'marcoshoya_marquejogobundle_customer[username]' => 'customerbook@marquejogo.com',
            'marcoshoya_marquejogobundle_customer[password]' => 'password',
        ));

        $this->client->submit($formBook);
        $crawler = $this->client->followRedirect();

        // information form (valid)
        $formBook = $crawler->selectButton('Continuar')->form(array(
            'marcoshoya_marquejogobundle_customer[name]' => 'Customer Book',
            'marcoshoya_marquejogobundle_customer[username]' => 'customerbook@marquejogo.com',
            'marcoshoya_marquejogobundle_customer[phone]' => '9999-8877',
            'marcoshoya_marquejogobundle_customer[password]' => 'password',
            'marcoshoya_marquejogobundle_customer[team][0][name]' => 'Book Team'
        ));

        $this->client->submit($formBook);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Reserva finalizada!")')->count());

        $this->clearData(true);
    }

}
