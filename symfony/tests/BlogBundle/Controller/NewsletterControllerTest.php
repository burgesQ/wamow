<?php

namespace BlogBundle\Tests\Controller;

use ToolsBundle\Tests\WamowTestCase;

class NewsletterControllerTest extends WamowTestCase
{
    public function testListAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/en/newsletters');

        self::assertGreaterThan(
            0,
            $crawler->filter("div.newsletter-left")->count()
        );
    }
}
