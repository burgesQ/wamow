<?php

namespace BlogBundle\Tests\Controller;

use ToolsBundle\Tests\WamowTestCase;

class NewsletterControllerTest extends WamowTestCase
{
    public function testListAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/en/newsletter');

        $this->assertEquals(
            0,
            $crawler->filter("newsletter-left")->count()
        );
    }
}
