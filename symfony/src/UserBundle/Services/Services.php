<?php

namespace UserBundle\Services;

use Smalot\PdfParser\Parser;

class Services
{
    /**
     * Parse the resume of the user
     *
     * @param $em
     * @param $user
     * @param $emSave
     */
    public function parseResume($em, $user, $emSave)
    {
        // get all resume from the user
        $resumes = $em->findByUser($user);

        // get last resume upload
        $resume = end($resumes);

        if ($resume != null) {
            // get the pdf parser
            $parser = new Parser();
            // extract data from file
            $pdf = $parser->parseFile($resume->getWebPath());
            // get mail address from pdf
            $mailAdress = $this->getMail($pdf->getText());

            // add them as secret mail
            if ($mailAdress[0] != null)
                $user->addSecretMail($mailAdress[0]);

            // save content of the resume in the entity
            $resume->setContent($pdf->getText());

            // save user and resume new data
            $emSave->flush();
        }
    }

    /**
     * Extract mail address from string
     *
     * @param $str
     *
     * @return mixed
     */
    public function getMail($str)
    {
        $mails     = "";
        $pattern = "/(?:[A-Za-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[A-Za-z0-9-]*[A-Za-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";

        preg_match_all($pattern, $str, $mails);

        return $mails;
    }
}