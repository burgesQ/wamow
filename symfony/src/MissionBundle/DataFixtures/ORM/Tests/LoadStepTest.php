<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\Step;

class LoadAddress implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $mission = $manager->getRepository('MissionBundle:Mission')
                    ->findOneBy(array('title' => "Fixture Test"));

        $step = new Step(10, 10);
        $step->setMission($mission);
        $step->setUpdateDate(new \DateTime());
        $step->setStatus(1);
        $step->setPosition(1);

        $step2 = new Step(3, 1);
        $step2->setMission($mission);
        $step2->setUpdateDate(new \DateTime());
        $step2->setPosition(2);

        $step3 = new Step(1, 0);
        $step3->setMission($mission);
        $step3->setUpdateDate(new \DateTime());
        $step3->setPosition(3);

        $manager->persist($step);
        $manager->persist($step2);
        $manager->persist($step3);

        $manager->flush();
    }
}
