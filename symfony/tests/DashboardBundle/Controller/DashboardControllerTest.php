<?php

namespace DashboardBundle\Tests\Controller;

use ToolsBundle\Tests\WamowTestCase;

class DashboardControllerTest extends WamowTestCase
{
    /**
     * Test get dashboard not logged
     * Test get advisor dashboard
     * Test get contractor dashboard
     */
    public function testDashboardAction()
    {
        $client = static::createClient();
        $client->request('GET', '/en/dashboard');
        self::assertTrue(
            $client->getResponse()->isClientError()
        );

        $crawler = $this->doLogin("contractor@one.com", "password");
        // follow / to /dash ; follow /dash to /en/dash
        $this->client->followRedirect();
        $crawler = $this->client->followRedirect();

        self::assertEquals(
            1,
            $crawler->filter("a.sidebar-progress-button")->count()
        );

        $this->doLogin("fake-vanessa.albek@gt-executive.com", "password");
        // follow / to /dash
        $this->client->followRedirect();
        // follow /dash to /en/dash
        $crawler = $this->client->followRedirect();

        self::assertEquals(
            0,
            $crawler->filter("a.sidebar-progress-button")->count()
        );
    }
}
