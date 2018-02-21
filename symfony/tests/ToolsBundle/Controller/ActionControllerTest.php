<?php

namespace ToolsBundle\Tests\Controller;

use ToolsBundle\Tests\WamowTestCase;

class ActionControllerTest extends WamowTestCase
{
    /**
     * Test getting French and English in english
     * Test getting French and English in french
     */
    public function testLanguagesAutocompleteAction()
    {
        $this->performClientRequest(
            'GET',
            "en/language/autocomplete?_=1519213039122&page=1&page_limit=10&q=en",
            [],
            "fake-vanessa.albeck@gmail.com",
            "password"
        );
        $response_en = $this->client->getResponse();

        $this->performClientRequest(
            'GET',
            "fr/language/autocomplete?_=1519213039122&page=1&page_limit=10&q=an",
            [],
            "fake-vanessa.albeck@gmail.com",
            "password"
        );
        $response_fr = $this->client->getResponse();

        $values_en = json_decode($response_en->getContent());
        $values_fr = json_decode($response_fr->getContent());

        $val_en = [];
        $val_fr = [];

        foreach ($values_en as $oneVal)
            foreach ($oneVal as $key => $val)
                $val_en[] = $val->text;

        foreach ($values_fr as $oneVal)
            foreach ($oneVal as $key => $val)
                $val_fr[] = $val->text;

        self::assertContains("French", $val_en, "English language autocomplete is broken");
        self::assertContains("English", $val_en, "English language autocomplet is broken");

        self::assertContains("Francais", $val_fr, "French language autocomplete is broken");
        self::assertContains("Anglais", $val_fr, "French language autocomplete is broken");
    }

    /**
     * Test not logged = 404
     */
    public function testDownloadProposalAction()
    {
        $this->performClientRequest(
            'GET',
            "fr/proposal/download/1",
            []
        );

        $response = $this->client->getResponse();

        self::assertTrue($response->isClientError());

        // should also check mission
        // should also check by step of the user mission
        // should also check user company
    }

    /**
     * Test not logged = 404
     */
    public function testDownloadProposalAdvisorAction()
    {
        $this->performClientRequest(
            'GET',
            "fr/proposal/download/1",
            []
        );

        $response = $this->client->getResponse();

        self::assertTrue($response->isClientError());

        // should also check bad mission
        // should also check by step of the user mission
        // should also check user <-> usermission
    }

    /**
     * Test not logged = 404
     * Test not Contractor = 404
     */
    public function testDownloadResumeAction()
    {
        $this->performClientRequest(
            'GET',
            "fr/proposal/download/1",
            []
        );

        $response = $this->client->getResponse();

        self::assertTrue($response->isClientError());

        $this->performClientRequest(
            'GET',
            "fr/proposal/download/1",
            [],
            "fake-vanessa.albeck@gmail.com",
            "password"
        );

        $response = $this->client->getResponse();

        self::assertTrue($response->isClientError());

        // should also check bad usermission
        // should also check by step of the user mission
        // should also check user <-> usermission
    }
}
