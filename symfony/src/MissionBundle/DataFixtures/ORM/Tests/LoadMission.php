<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\Mission;
use TeamBundle\Entity\Team;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadMission extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $contractor = $manager->getRepository('UserBundle:User')
                    ->findOneBy(array('id' => "1"));

        $team = new Team(1, $contractor);

        $contractor = $manager->getRepository('UserBundle:User')
                    ->findOneBy(array('id' => "2"));
        $team->addUser($contractor);

        $team->setStatus(1);
        $manager->persist($team);
        $manager->flush();

        $address = $manager->getRepository('ToolsBundle:Address')
                    ->findOneBy(array('street' => "18 Avenue Franklin Roosevelt"));
        $professionalExpertise = $manager->getRepository('MissionBundle:ProfessionalExpertise')
                    ->findOneBy(array('name' => 'professionalexpertises.operations'));
        $missionKind = $manager->getRepository('MissionBundle:MissionKind')
                    ->findOneBy(array('name' => 'typemissions.change'));
        $businessPractice = $manager->getRepository('MissionBundle:BusinessPractice')
                    ->findOneBy(array('name' => 'businesspractice.industry'));
        $language = $manager->getRepository('ToolsBundle:Language')
                    ->findOneBy(array('name' => 'English'));

        $mission = new Mission(3, $team, 1, "804sadf304efw", $contractor->getCompany());

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

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 9;
    }
}
