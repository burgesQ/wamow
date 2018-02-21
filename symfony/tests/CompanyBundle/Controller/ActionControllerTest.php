<?php

namespace CompanyBundle\Tests\Controller;

use ToolsBundle\Tests\WamowTestCase;

class ActionControllerTest extends WamowTestCase
{
    /**
     * Test getting French and English in english
     * Test getting French and English in french
     */
    public function testInspectorAutocompleteAction()
    {
        $response = $this->performClientRequest(
            'GET',
            "en/inspectors/autocomplete?_=1519216101731&page=1&page_limit=10&q=gr",
            [],
            "vanessa.albeck@gmail.com",
            "password"
        );
        $values = json_decode($response->getContent());
        $haystack = [];

        foreach ($values->results as $oneVal)
            foreach ($oneVal as $key => $val)
                $haystack[] = $val;

        self::assertContains("Grant Thornton", $haystack, "English language autocomplete is broken");
    }
}
