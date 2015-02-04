<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Provider;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Provider\DashboardTest;

/**
 * ScheduleControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ScheduleControllerTest extends DashboardTest
{

    public function testCalendar()
    {
        $provider = $this->logIn();

        // get initial dates
        $current = $this->scheduleService->getDate();
        $navbar = $this->scheduleService->createNavbar($current->format('Y'), $current->format('m'), $current->format('d'));

        //#1 main calendar
        $uri = $this->router->generate('schedule', array('year' => $current->format('Y'), 'month' => $current->format('m')));

        $crawler = $this->client->request('GET', $uri);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter("h3:contains(\"{$navbar['curr']['title']['index']}\")")->count());
        
        // #2 day page
        $uri = $this->router->generate('schedule_show', array('year' => $current->format('Y'), 'month' => $current->format('m'), "day" => $current->format('d')));
        
        $crawler = $this->client->request('GET', $uri);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter("h3:contains(\"{$navbar['curr']['title']['show']}\")")->count());

        $product = $this->em
            ->getRepository('MarcoshoyaMarquejogoBundle:ProviderProduct')
            ->findOneBy(array(
                'provider' => $provider,
                'isActive' => true
            ));
        
        $uri = $this->router->generate('schedule_edit', array(
            'year' => $current->format('Y'), 
            'month' => $current->format('m'), 
            "day" => $current->format('d'),
            "hour" => '20',
            "product" => $product->getId()
        ));
        
        $crawler = $this->client->request('GET', $uri);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter("h2:contains(\"{$navbar['curr']['title']['edit']}\")")->count());
    
    }
}
