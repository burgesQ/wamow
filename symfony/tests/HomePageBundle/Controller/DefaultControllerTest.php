<?php

namespace HomePageBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use ToolsBundle\Tests\WamowTestCase;

class DefaultControllerTest extends WamowTestCase
{
    // test view adv
    /**
     * Test no logged; count sign up button
     * Test allready logged so redirection to dashboard
     */
    public function testAdvisorAction()
    {
        $crawler = $this->performClientRequest(
            ' GET',
            '/en/'
        );

        self::assertEquals(
            3,
            $crawler->filter('a:contains("SIGN UP")')->count()
        );

        self::assertEquals(
            1,
            $crawler->filter('a:contains("I REPRESENT A COMPANY")')->count()
        );

        $this->performClientRequest(
            ' GET',
            '/en/',
            [],
            "contractor@one.com",
            "password"
        );

        self::assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testContractorAction()
    {
        $crawler = $this->performClientRequest(
            ' GET',
            '/en/corporation'
        );

        self::assertEquals(
            1,
            $crawler->filter('button:contains("LET\'S START")')->count()
        );

        // should test form

        $this->performClientRequest(
            ' GET',
            '/en/',
            [],
            "contractor@one.com",
            "password"
        );

        self::assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testNoLocalAction(){}
    public function testNoLocalNewsletterAction(){}
    public function testDummySignUpAction(){}
    public function testContactAction(){}
}
