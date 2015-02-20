<?php

namespace Marcoshoya\MarquejogoBundle\Tests\Controller\Adm;

use Marcoshoya\MarquejogoBundle\Tests\Controller\Adm\AdmBaseTest;

/**
 * ReportControllerTest
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ReportControllerTest extends AdmBaseTest
{
    public function testReportBook()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', $this->router->generate('adm_report_book'));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Relatório de Reservas")')->count());
    }
}
