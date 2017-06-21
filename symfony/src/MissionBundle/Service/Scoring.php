<?php

namespace MissionBundle\Service;

use MissionBundle\Entity\UserMission;

 class Scoring
{
    protected $em;
    protected $scoringRules;
    protected $weightBusinessPractice, $weightProfessionalExpertise, $weightCompanySize, $weightMissionKind, $weightLocation, $weightCertification, $weightWorkExperience;
    protected $scoringMin;

    public function __construct(\Doctrine\ORM\EntityManager $em, $scoringRules, $weightBusinessPractice, $weightProfessionalExpertise, $weightCompanySize, $weightMissionKind, $weightLocation, $weightCertification, $weightWorkExperience, $scoringMin)
    {
        $this->em = $em;
        $this->scoringRules = $scoringRules;
        $this->weightBusinessPractice = $weightBusinessPractice;
        $this->weightProfessionalExpertise = $weightProfessionalExpertise;
        $this->weightCompanySize = $weightCompanySize;
        $this->weightMissionKind = $weightMissionKind;
        $this->weightLocation = $weightLocation;
        $this->weightCertification = $weightCertification;
        $this->weightWorkExperience = $weightWorkExperience;
        $this->scoringMin = $scoringMin;
    }

    private function getScoringStep($mission)
    {
        return count($mission->getScoringHistory()) ? count($mission->getScoringHistory()) : 0;
    }

    private function updateNextScoring($mission)
    {
        $scoringRules = $this->scoringRules;
        $scoringStep = $this->getScoringStep($mission);
        if (isset($scoringRules[$scoringStep + 1])) {
            $nbDays = $scoringRules[$scoringStep + 1][0];
            $mission->setNextUpdateScoring(new \DateTime("+".$nbDays." days"));
        } else {
            $mission->setNextUpdateScoring(null);
        }
    }
    public function initializeMission($mission)
    {
        $this->updateActivated($mission);
        $this->updateScorings($mission);
    }

    public function getScoring($mission, $userMission)
    {
        $scoringDetails = array();
        $score = 0;
        $user = $userMission->getUser();
        // secteur activité
        $nb = 0;
        if ($user->getBusinessPractice()->contains($mission->getBusinessPractice())) {
            $score += $this->weightBusinessPractice;
            $nb++;
        }
        if ($nb) {
            $scoringDetails["BusinessPractice"] = array("weight" => $this->weightBusinessPractice, "nb" => $nb);
            $nb = 0;
        }
        // experience technique
        if ($user->getProfessionalExpertise()->contains($mission->getProfessionalExpertise())) {
            $score += $this->weightProfessionalExpertise;
            $nb++;
        }
        if ($nb) {
            $scoringDetails["ProfessionalExpertise"] = array("weight" => $this->weightProfessionalExpertise, "nb" => $nb);
            $nb = 0;
        }

        $experiences = $user->getUserWorkExperiences();
        $companySize = $mission->getCompanySize();
        $nbSize = 0;
        $nbContinent = 0;
        $nbWorkExperience = 0;
        foreach ($experiences as $experience) {
            // taille entreprise
            if ($experience->getCompanySizes()->contains($companySize)) {
                $score += $this->weightCompanySize;
                $nbSize++;
            }
            // zone géographique
            foreach ($experience->getContinents() as $userContinent) {
                if ($mission->getContinents()->contains($userContinent)) {
                    $score += $this->weightLocation;
                    $nbContinent++;
                }
            }
            // archétype mission
            if ($mission->getWorkExperience() && $mission->getWorkExperience() == $experience->getWorkExperience()) {
                $score += $this->weightWorkExperience;
            }
        }
        if ($nbSize) {
            $scoringDetails["CompanySize"] = array("weight" => $this->weightCompanySize, "nb" => $nbSize);
        }
        if ($nbContinent) {
            $scoringDetails["Continent"] = array("weight" => $this->weightLocation, "nb" => $nbContinent);
        }
        if ($nbWorkExperience) {
            $scoringDetails["tWorkExperience"] = array("weight" => $this->weightWorkExperience, "nb" => $nbWorkExperience);
        }

        // Certifications
        foreach ($user->getCertifications() as $certification) {
            if ($mission->getCertifications()->contains($certification)) {
                $score += $this->weightCertification;
                $nb++;
            }
        }
        if ($nb) {
            $scoringDetails["Certification"] = array("weight" => $this->weightCertification, "nb" => $nb);
            $nb = 0;
        }

        foreach ($user->getMissionKind() as $missionKind) {
            // type mission
            if ($mission->getMissionKinds()->contains($missionKind)) {
                $score += $this->weightMissionKind;
                $nb++;
            }
        }
        if ($nb) {
            $scoringDetails["MissionKind"] = array("weight" => $this->weightMissionKind, "nb" => $nb);
            $nb = 0;
        }

        // rotation
        if ($user->getScoringBonus()) {
            $score += $user->getScoringBonus();
            $scoringDetails["ScoringBonus"] = array("weight" => $user->getScoringBonus());
        }
        
        $userMission->setScoreDetails($scoringDetails);

        return $score;
    }

    public function updateUserMissions($mission)
    {
        $nbNewUserMissions = 0;
        $users = $this->em->getRepository("MissionBundle:Mission")->findUsersByMission($mission);
        foreach ($users as $user) {
            $userMission = $this->em->getRepository("MissionBundle:UserMission")->findOneBy(array("user" => $user, "mission" => $mission));
            if (!$userMission) {
                $nbNewUserMissions++;
                $userMission = new UserMission($user, $mission);
            }
            $this->em->persist($userMission);
        }
        return $nbNewUserMissions;
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
        $scoringStep = $this->getScoringStep($mission);
        $scoringRules = $this->scoringRules;
        // $matchedUserMissions = $this->em->getRepository("MissionBundle:UserMission")->findBy("mission" => $mission, "status" => UserMission::MATCHED);
        $userMissions = $this->em->getRepository("MissionBundle:UserMission")->findOrderedByMission($mission, $scoringRules[$scoringStep][1], $this->scoringMin);
        // for JSON
        $scoringHistory[$scoringStep] = array();
        $scorings = array();
        foreach ($userMissions as $userMission) {
            $userMission->setStatus(UserMission::MATCHED);
            $scorings[$userMission->getUser()->getId()] = $userMission->getScore();
        }
        $scoringHistory[$scoringStep] = $scorings;
        $this->updateNextScoring($mission);
        $mission->setScoringHistory($scoringHistory);
        $this->em->flush();
        return count($userMissions);
    }
}
