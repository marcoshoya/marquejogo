<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Provider;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Marcoshoya\MarquejogoBundle\Entity\Provider;

/**
 * DashboardTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 *
 * @see http://symfony.com/doc/current/cookbook/testing/simulating_authentication.html
 */
class DashboardTest extends WebTestCase
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
    protected $router = null;

    /**
     * @var Marcoshoya\MarquejogoBundle\Service\ScheduleService
     */
    protected $scheduleService;


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

        $this->scheduleService = static::$kernel->getContainer()
            ->get('marcoshoya_marquejogo.service.schedule')
        ;
    }

    /**
     * Make login on provider panel
     *
     * @return Provider
     */
    protected function logIn()
    {
        $session = $this->client->getContainer()->get('session');
        $firewall = 'provider';

        $provider = $this->em->getRepository('MarcoshoyaMarquejogoBundle:Provider')->find(1);
        if (!$provider) {
            $this->fail("Provider not found");
        }

        $token = new UsernamePasswordToken($provider, null, $firewall, array('ROLE_PROVIDER'));

        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);

        return $provider;
    }

    /**
     * General test for dashboard
     */
    public function testDashboard()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/fornecedor/');

        $this->assertTrue($this->client->getContainer()->get('security.context')->isGranted('ROLE_PROVIDER'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Painel Fornecedor")')->count());
    }
}
