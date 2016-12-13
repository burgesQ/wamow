<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\Mission;
use TeamBundle\Entity\Team;

class LoadMission implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $contractor = $manager->getRepository('UserBundle:User')
                    ->findOneBy(array('id' => "1"));
                    
        $team = new Team(1);
        $team->addUser($contractor);
        $team->setStatus(1);
        $manager->persist($team);
        $manager->flush();

        $address = $manager->getRepository('ToolsBundle:Address')
                    ->findOneBy(array('street' => "18 Avenue Franklin Roosevelt"));
        $professionalExpertise = $manager->getRepository('MissionBundle:ProfessionalExpertise')
                    ->findOneBy(array('name' => "IT"));
        $missionKind = $manager->getRepository('MissionBundle:MissionKind')
                    ->findOneBy(array('name' => 'CRM'));
        $businessPractice = $manager->getRepository('MissionBundle:BusinessPractice')
                    ->findOneBy(array('name' => 'Public Administration'));
        $language = $manager->getRepository('ToolsBundle:Language')
                    ->findOneBy(array('name' => 'English'));

        $mission = new Mission(3, $team, 1, "804sadf304efw");

        $mission->setAddress($address);
        $mission->setProfessionalExpertise($professionalExpertise);
        $mission->setMissionKind($missionKind);
        $mission->addLanguage($language);
        $mission->setBusinessPractice($businessPractice);

        $mission->setTitle("Fixture Test");
        $mission->setResume("test");
        $mission->setConfidentiality(0);
        $mission->setStatus(1);
        $mission->setTelecommuting(0);
        $mission->setInternational(0);
        $mission->setBudget(700);
        $mission->setApplicationEnding(new \DateTime("01-01-2017"));
        $mission->setMissionBeginning(new \DateTime("01-01-2018"));
        $mission->setMissionEnding(new \DateTime("01-01-2019"));
        $manager->persist($mission);
        $team->setMission($mission);
        $manager->persist($team);
        $manager->flush();
    }
}
