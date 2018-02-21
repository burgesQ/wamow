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
            "/en/dashboard",
            $this->client->getResponse()->headers->get("location")
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
            $crawler->filter('button:contains("Let\'s start")')->count()
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
            "/en/dashboard",
            $this->client->getResponse()->headers->get("location")
        );
    }

    public function testNoLocalAction()
    {
        $this->performClientRequest(
            ' GET',
            '/'
        );

        self::assertEquals(
            "/en/",
            $this->client->getResponse()->headers->get("location")
        );
    }

    public function testNoLocalNewsletterAction()
    {
        $this->performClientRequest(
            ' GET',
            '/newsletters'
        );

        self::assertEquals(
            "/en/newsletters",
            $this->client->getResponse()->headers->get("location")
        );
    }

    public function testDummySignUpAction()
    {
        $this->performClientRequest(
            ' GET',
            '/sign-up/'
        );

        self::assertEquals(
            "/en/newsletters",
            $this->client->getResponse()->headers->get("location")
        );
    }

    public function testContactAction()
    {
        $this->performClientRequest(
            ' GET',
            '/contact/'
        );

        self::assertEquals(
            "/en/contact",
            $this->client->getResponse()->headers->get("location")
        );
    }
}
