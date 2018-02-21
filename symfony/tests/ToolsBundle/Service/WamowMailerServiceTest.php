<?php

namespace ToolsBundle\Tests\Service;

use ToolsBundle\Service\WamowMailerService;
use ToolsBundle\Tests\WamowTestCase;

class WamowMailerServiceTest extends WamowTestCase
{
    /**
     * @return int
     */
    public function testGetSenderSignature()
    {
        $senderSignatures = [];

        /** @var WamowMailerService $mailer */
        $mailer = $this->getService('wamow.mailer');

        for ($i = 0; $i < 10; $i++)
            $senderSignatures[] = $mailer->getSenderSignature();

        $lastOne = "";

        foreach ($senderSignatures as $oneSignature)
            if ($lastOne)
                $lastOne = $oneSignature[0];
            elseif ($lastOne != $oneSignature[0]) {
                self::assertTrue(true);

                return 0;
            }

        self::assertTrue(false, "The 9 signatures are the same.");

        return -1;
    }
}
