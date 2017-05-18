<?php

namespace MissionBundle\Service;

use MissionBundle\Entity\UserMission;

 class Scoring
{
    protected $em;

    protected $weightBusinessPractice, $weightProfessionalExpertise, $weightCompanySize, $weightMissionKind, $weightLocation;

    public function __construct(\Doctrine\ORM\EntityManager $em, $weightBusinessPractice, $weightProfessionalExpertise, $weightCompanySize, $weightMissionKind, $weightLocation)
    {
        $this->em = $em;
        $this->weightBusinessPractice = $weightBusinessPractice;
        $this->weightProfessionalExpertise = $weightProfessionalExpertise;
        $this->weightCompanySize = $weightCompanySize;
        $this->weightMissionKind = $weightMissionKind;
        $this->weightLocation = $weightLocation;
    }

    private function getScoringRules()
    {
        return array(0 => array(0, 50), 1 => array(2, 100), 2 => array(4, 200), 3 => array(6, null));
    }

    private function getScoringStep($mission)
    {
        return count($mission->getScoringHistory()) ? count($mission->getScoringHistory()) - 1 : 0;
    }

    private function updateNextScoring($mission)
    {
        $scoringRules = $this->getScoringRules();
        $scoringStep = $this->getScoringStep($mission);
        if (isset($scoringRules[$scoringStep + 1])) {
            $nbDays = $scoringRules[$scoringStep + 1][0];
            $mission->setNextUpdateScoring(strtotime("+".$nbDays." days"));
        } else {
            $mission->setNextUpdateScoring(null);
        }
    }
    public function initializeMission($mission)
    {
        $this->updateActivated($mission);
        $mission->setNextUpdateScoring(strtotime("+".." days"))
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
        // TODO ! == MissionKind 'typemissions.certification' ? How ? => quentin

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
        // TODO : kesako ? = valuable work experience
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
        $this->updateNextScoring($mission);
        $scoringStep = $this->getScoringStep($mission);
        // TODO : Step Entity ? Config ?
        $scoringRules = $this->getScoringRules();
        // $activatedUserMissions = $this->em->getRepository("MissionBundle:UserMission")->findBy("mission" => $mission, "status" => UserMission::ACTIVATED);
        $userMissions = $this->em->getRepository("MissionBundle:UserMission")->findOrderedByMission($mission, $scoringRules[$scoringStep][1]);
        // for JSON
        $scoringHistory[$scoringStep] = array();
        $scorings = array();
        foreach ($userMissions as $userMission) {
            $userMission->setStatus(UserMission::ACTIVATED);
            $scorings[$userMission->getUser()->getId()] = $userMission->getScore();
        }
        $scoringHistory[$scoringStep] = $scorings;
        $mission->setScoringHistory($scoringHistory);
        $this->em->flush();
        return count($userMissions);
    }
}
