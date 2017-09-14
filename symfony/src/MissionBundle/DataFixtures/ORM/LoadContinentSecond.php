<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\Continent;

class LoadContinentSecond extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $names = [
            'continent.africa',
            'continent.eurasia',
            'continent.middle_east'
        ];

        foreach ($names as $name) {
            $continent = new Continent($name);
            $manager->persist($continent);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}
