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
     * Setup functions
     */
    public function setUp()
    {
        $this->client = static::createClient();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

    /**
     * Test main
     */
    public function testMain()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertTrue($this->client->getContainer()->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("MarqueJogo.com")')->count());
    }
}
