<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Adm;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Adm\AdmBaseTest;

/**
 * DashboardControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class DashboardControllerTest extends AdmBaseTest
{

    /**
     * General test for dashboard
     *
     * GET /adm
     * HTTP/1.1 200 OK
     */
    public function testDashboard()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', $this->router->generate('_adm_dash'));

        $this->assertTrue($this->client->getContainer()->get('security.context')->isGranted('ROLE_ADMIN'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("MarqueJogo.com - Administrador")')->count());
    }

}
