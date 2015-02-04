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
        $this->logIn();

        // get initial dates
        $current = new \DateTime();
        $navbar = $this->scheduleService->createNavbar($current);

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
    }
}
