<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * MainControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class MainControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client = null;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em = null;
    
    /**
     * @var Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    protected $router;

    /**
     * Setup functions
     */
    public function setUp()
    {
        $this->client = static::createClient();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
        
        $this->router = static::$kernel->getContainer()
            ->get('router')
        ;
    }

    /**
     * Test main
     */
    public function testMain()
    {   
        $crawler = $this->client->request('GET', $this->router->generate('marquejogo_homepage'));

        $this->assertTrue($this->client->getContainer()->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("MarqueJogo.com")')->count());
    }
}
