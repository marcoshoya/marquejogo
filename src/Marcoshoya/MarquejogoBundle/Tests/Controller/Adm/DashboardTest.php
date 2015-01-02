<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Adm;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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
     * Make login on admin
     */
    protected function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'admin';
        $token = new UsernamePasswordToken('admin', null, $firewall, array('ROLE_ADMIN'));
        
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
    
    /**
     * General test for dashboard
     */
    public function testDashboard()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/adm/');
        
        $this->assertTrue($this->client->getContainer()->get('security.context')->isGranted('ROLE_ADMIN'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("MarqueJogo.com - Administrador")')->count());
    }
}
