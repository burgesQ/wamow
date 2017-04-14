<?php

namespace MissionBundle\DataFixtures\ORM\Tests;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\Step;

class LoadStep extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        foreach ($manager->getRepository('MissionBundle:Mission')->findAll() as $oneMission) {

            $step  = new Step(10, 10);
            $step2 = new Step(3, 1);
            $step3 = new Step(1, 0);

            $step
                ->setMission($oneMission)
                ->setUpdateDate(new \DateTime())
                ->setStatus(1)
                ->setPosition(1);
            $step2
                ->setMission($oneMission)
                ->setUpdateDate(new \DateTime())
                ->setPosition(2);
            $step3
                ->setMission($oneMission)
                ->setUpdateDate(new \DateTime())
                ->setPosition(3);

            $manager->persist($step);
            $manager->persist($step2);
            $manager->persist($step3);
        }

        // flush steps steps
        $manager->flush();
    }

    public function getOrder()
    {
        return 14;
    }

}

