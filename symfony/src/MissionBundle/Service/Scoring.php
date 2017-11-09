<?php

namespace MissionBundle\Service;

use MissionBundle\Entity\UserMission;

class Scoring
{
    protected $em;
    protected $scoringRules;
    protected $weightBusinessPractice, $weightProfessionalExpertise, $weightCompanySize, $weightMissionKind, $weightLocation, $weightCertification, $weightWorkExperience;
    protected $scoringMin;

    public function __construct(\Doctrine\ORM\EntityManager $em,
                                $scoringRules,
                                $weightBusinessPractice,
                                $weightProfessionalExpertise,
                                $weightCompanySize,
                                $weightMissionKind,
                                $weightLocation,
                                $weightCertification,
                                $weightWorkExperience,
                                $scoringMin)
    {
        $this->em                          = $em;
        $this->scoringRules                = $scoringRules;
        $this->weightBusinessPractice      = $weightBusinessPractice;
        $this->weightProfessionalExpertise = $weightProfessionalExpertise;
        $this->weightCompanySize           = $weightCompanySize;
        $this->weightMissionKind           = $weightMissionKind;
        $this->weightLocation              = $weightLocation;
        $this->weightCertification         = $weightCertification;
        $this->weightWorkExperience        = $weightWorkExperience;
        $this->scoringMin                  = $scoringMin;
    }

    public function initializeMission($mission)
    {
        $this->updateActivated($mission);
        $this->updateScorings($mission);
    }

    /**
     * @param \MissionBundle\Entity\Mission $mission
     * @return int
     */
    public function updateActivated($mission)
    {
        if ($mission->getStatusGenerator() === \MissionBundle\Entity\Mission::DONE) {

            $scoringHistory = $mission->getScoringHistory();
            $scoringStep    = $this->getScoringStep($mission);
            $scoringRules   = $this->scoringRules;
            // $matchedUserMissions = $this->em->getRepository("MissionBundle:UserMission")->findBy("mission" => $mission, "status" => UserMission::MATCHED);

            $userMissions =
                $this->em->getRepository("MissionBundle:UserMission")
                    ->findOrderedByMission($mission, $scoringRules[$scoringStep][1], $this->scoringMin);
            // for JSON
            $scoringHistory[$scoringStep] = [];
            $scorings                     = [];
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

        return 0;
    }

    /**
     * @param \MissionBundle\Entity\Mission $mission
     */
    private function updateNextScoring($mission)
    {
        $scoringRules = $this->scoringRules;
        $scoringStep  = $this->getScoringStep($mission) > 2 ? 2 : $this->getScoringStep($mission);

        echo "\n\nUpdate Next Date\n\n";


        if (isset($scoringRules[$scoringStep + 1])) {
            $nbDays = $scoringRules[$scoringStep + 1][0];
            $mission->setNextUpdateScoring(new \DateTime("+" . $nbDays . " days"));
        } else {
            $mission->setNextUpdateScoring(null);
        }
    }

    /**
     * @param \MissionBundle\Entity\Mission $mission
     * @return int
     */
    private function getScoringStep($mission)
    {

        return $mission->getActualStep()->getStatus();

//        return count($mission->getScoringHistory()) ? count($mission->getScoringHistory()) : 0;
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

    public function getScoring($mission, $userMission)
    {
        $scoringDetails = [];
        $score          = 0;
        $user           = $userMission->getUser();
        // secteur activité
        $nb = 0;
        if ($user->getBusinessPractice()->contains($mission->getBusinessPractice())) {
            $score += $this->weightBusinessPractice;
            $nb++;
        }
        if ($nb) {
            $scoringDetails["BusinessPractice"] = ["weight" => $this->weightBusinessPractice, "nb" => $nb];
            $nb                                 = 0;
        }
        // experience technique
        if ($user->getProfessionalExpertise()->contains($mission->getProfessionalExpertise())) {
            $score += $this->weightProfessionalExpertise;
            $nb++;
        }
        if ($nb) {
            $scoringDetails["ProfessionalExpertise"] = ["weight" => $this->weightProfessionalExpertise, "nb" => $nb];
            $nb                                      = 0;
        }

        $experiences      = $user->getUserWorkExperiences();
        $companySize      = $mission->getCompanySize();
        $nbSize           = 0;
        $nbContinent      = 0;
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
                $nbWorkExperience++;
            }
        }
        if ($nbSize) {
            $scoringDetails["CompanySize"] = ["weight" => $this->weightCompanySize, "nb" => $nbSize];
        }
        if ($nbContinent) {
            $scoringDetails["Continent"] = ["weight" => $this->weightLocation, "nb" => $nbContinent];
        }
        if ($nbWorkExperience) {
            $scoringDetails["tWorkExperience"] = ["weight" => $this->weightWorkExperience, "nb" => $nbWorkExperience];
        }

        // Certifications
        foreach ($user->getCertifications() as $certification) {
            if ($mission->getCertifications()->contains($certification)) {
                $score += $this->weightCertification;
                $nb++;
            }
        }
        if ($nb) {
            $scoringDetails["Certification"] = ["weight" => $this->weightCertification, "nb" => $nb];
            $nb                              = 0;
        }

        foreach ($user->getMissionKind() as $missionKind) {
            // type mission
            if ($mission->getMissionKinds()->contains($missionKind)) {
                $score += $this->weightMissionKind;
                $nb++;
            }
        }
        if ($nb) {
            $scoringDetails["MissionKind"] = ["weight" => $this->weightMissionKind, "nb" => $nb];
            $nb                            = 0;
        }

        // rotation
        if ($user->getScoringBonus()) {
            $score                          += $user->getScoringBonus();
            $scoringDetails["ScoringBonus"] = ["weight" => $user->getScoringBonus()];
        }

        $userMission->setScoreDetails($scoringDetails);

        return $score;
    }

    public function updateUserMissions($mission)
    {
        $nbNewUserMissions = 0;
        $users             = $this->em->getRepository("MissionBundle:Mission")->findUsersByMission($mission);
        foreach ($users as $user) {
            $userMission =
                $this->em->getRepository("MissionBundle:UserMission")->findOneBy([
                    "user"    => $user,
                    "mission" => $mission
                ]);
            if (!$userMission) {
                $nbNewUserMissions++;
                $userMission = new UserMission($user, $mission);
            }
            $this->em->persist($userMission);
        }

        return $nbNewUserMissions;
    }
}
