<?php

namespace UserBundle\Services;

use UserBundle\Entity\User;
use UserBundle\Entity\ElasticUser;

class Services
{
    /**
     * Extract mail address from string
     */
    public function getMail($str)
    {
        $url = $str;
        $pattern="/(?:[A-Za-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[A-Za-z0-9-]*[A-Za-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";

        preg_match_all($pattern, $str, $url);

        return $url;
    }

    /**
     * Parse the resume of the user
     */
    public function parseResume($em, $user, $emSave)
    {
        $resumes = $em->findByUser($user);
        $resume = end($resumes);
        
        if ($resume != null) {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($resume->getWebPath());
            $mailAdress = $this->getMail($pdf->getText());

            if ($mailAdress[0] != null)
                $user->addSecretMail($mailAdress[0]);

            $emSave->persist($user);
            $emSave->flush();
        }
    }

    /**
     * Edit the ElasticUser
     */
    public function elasticSave($em, $user, $emSave)
    {
        if (($elasticUser = $user->getElasticUser()) == null)
        {
            $elasticUser = new ElasticUser($user);
            $elasticUser->setCreationDate($user->getCreationDate());
        }
        
        if (($resumes = $em->findByUser($user)) != null) {
            $resume = end($resumes);
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($resume->getWebPath());
            $elasticUser->setCvResume($pdf->getText());
        }            

        $elasticUser->setUserId($user->getId());
        $elasticUser->setUserResume($user->getUserResume());
        $elasticUser->setCountry($user->getCountry());
        
        foreach ($user->getLanguages() as $language)          
            $elasticUser->addLanguage($language->getName());
        foreach ($user->getBusinessPractice() as $businessPractice)
            $elasticUser->addBusinesspractice($businessPractice->getName());
        foreach ($user->getProfessionalExpertise() as $professionalExpertise)
            $elasticUser->addProfessionalExpertise($professionalExpertise->getName());
        foreach ($user->getMissionKind() as $missionKind)
            $elasticUser->addMissionKind($missionKind->getName());
        
        $elasticUser->setUpdateDate($user->getUpdateDate());
        $elasticUser->setDailyFeesMin($user->getDailyFeesMin());
        $elasticUser->setDailyFeesMax($user->getDailyFeesMax());
        
        $user->setElasticUser($elasticUser);

        $emSave->persist($elasticUser);
        $emSave->persist($user);
        $emSave->flush();
    }    
}