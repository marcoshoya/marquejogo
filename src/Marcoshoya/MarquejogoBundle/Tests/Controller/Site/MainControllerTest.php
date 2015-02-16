<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Marcoshoya\MarquejogoBundle\Entity\Provider;

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
     * Configure schedule
     * 
     * @param Provider $provider
     * @param \DateTime $date
     * 
     * @return \Marcoshoya\MarquejogoBundle\Entity\ScheduleItem
     */
    protected function configureSchedule(Provider $provider, \DateTime $date)
    {
        try {
            $product = new \Marcoshoya\MarquejogoBundle\Entity\ProviderProduct();
            $product->setProvider($provider);
            $product->setCategory('open');
            $product->setType('soccer');
            $product->setCapacity(10);
            $product->setIsActive(true);
            $product->setName('Book Product Test');

            $this->em->persist($product);
            $this->em->flush();

            $schedule = $this->em->getRepository('MarcoshoyaMarquejogoBundle:Schedule')->findOneBy(array(
                'provider' => $provider
            ));

            $scheduleItem = new \Marcoshoya\MarquejogoBundle\Entity\ScheduleItem();
            $scheduleItem->setSchedule($schedule);
            $scheduleItem->setProviderProduct($product);
            $scheduleItem->setAlocated(0);
            $scheduleItem->setPrice(99);
            $scheduleItem->setAvailable(1);
            $scheduleItem->setDate($date);

            $this->em->persist($scheduleItem);
            $this->em->flush();
            
            return $scheduleItem;
            
        } catch (\Exception $ex) {
            $this->fail('configureSchedule error: ' . $ex->getMessage());
        }
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
