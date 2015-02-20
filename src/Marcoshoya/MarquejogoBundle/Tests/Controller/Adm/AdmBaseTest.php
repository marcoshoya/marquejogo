<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Adm;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * AdmBaseTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class AdmBaseTest extends WebTestCase
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
     * @inheritDoc
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
     * @inheritDoc
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }
    
    /**
     * Make login on admin
     * 
     * @see http://symfony.com/doc/current/cookbook/testing/simulating_authentication.html
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
     * @inheritDoc
     */
    public function testBase()
    {
        $this->assertTrue(true);
    }
}
