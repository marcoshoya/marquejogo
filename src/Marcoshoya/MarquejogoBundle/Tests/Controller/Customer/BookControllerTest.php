<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Customer;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Customer\DashboardTest;

/**
 * BookControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BookControllerTest extends DashboardTest
{
    public function testBook()
    {
        $this->logIn();
        
        $crawler = $this->client->request('GET', $this->router->generate('customer_book_list'));
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Minhas Reservas")')->count());
        
    }
}
