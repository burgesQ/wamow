<?php

namespace MissionBundle\Service;

use MissionBundle\Entity\UserMission;

 class Scoring
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getScoring($mission, $userMission)
    {
        // TODO : Scoring in Config ?
        $score = 0;
        $user = $userMission->getUser();
        // secteur activité
        if ($user->getBusinessPractice()->contains($mission->getBusinessPractice())) {
            $score += 5;
        }
        // experience technique
        if ($user->getProfessionalExpertise()->contains($mission->getProfessionalExpertise())) {
            $score += 5;
        }
        // taille entreprise
        $experiences = $user->getExperienceShaping();
        $size = $mission->getCompany()->getSize();
        foreach ($experiences as $experience) {
            switch ($size) {
                case 0:
                    if ($experience->getSmallCompany()) {
                        $score += 1;
                    }
                    break;
                case 1:
                    if ($experience->getMediumCompany()) {
                        $score += 1;
                    }
                    break;
                case 2:
                    if ($experience->getLargeCompany()) {
                        $score += 1;
                    }
                    break;
            }
        }
        // certifications
        // TODO ! == MissionKind 'typemissions.certification' ? How ?

        // type mission
        foreach ($mission->getMissionKinds() as $missionKind) {
            if ($user->getMissionKind()->contains($missionKind)) {
                $score += 2;
            }
        }

        // zone géographique
        foreach ($experiences as $experience) {
            if ($mission->getAsia() && $experience->getAsia()) {
                $score += 1;
            } elseif ($mission->getNorthAmerica() && $experience->getNorthAmerica()) {
                $score += 1;
            } elseif ($mission->getEmea() && $experience->getEmea()) {
                $score += 1;
            } elseif ($mission->getSouthAmerica() && $experience->getSouthAmerica()) {
                $score += 1;
            }
        }
        // rotation
        $score += $user->getBonusPoints();

        // archétype mission
        // TODO : kesako ?
        return $score;
    }

    public function updateScorings($mission)
    {
        foreach ($mission->getUserMission() as $userMission) {
            $score = $this->getScoring($mission, $userMission);
            $userMission->setScore($score);
        }
        $this->em->flush();
        return;
    }

    public function updateActivated($mission) {
        $scoringHistory = $mission->getScoringHistory();
        // TODO : Activation step in Mission ?
        $activationStep = count($scoringHistory) ? count($scoringHistory) - 1 : 0;
        // TODO : Step Entity ? Config ?
        $nbSelected = array(0 => 50, 1 => 100, 2 => 200, 3 => null);
        // TODO : Reset previous Activated ? Only if status == ACTIVATED ? => not necessary ?
        // $activatedUserMissions = $this->em->getRepository("MissionBundle:UserMission")->findBy("mission" => $mission, "status" => UserMission::ACTIVATED);
        $userMissions = $this->em->getRepository("MissionBundle:UserMission")->findOrderedByMission($mission, $nbSelected[$activationStep]);
        // for JSON
        $scoringHistory[$activationStep] = array();
        $scorings = array();
        foreach ($userMissions as $userMission) {
            $userMission->setStatus(UserMission::ACTIVATED);
            $scorings[$userMission->getUser()->getId()] = $userMission->getScore();
        }
        $scoringHistory[$activationStep] = $scorings;
        $mission->setScoringHistory($scoringHistory);
        $this->em->flush();

    }
}
