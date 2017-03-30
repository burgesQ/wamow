<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ToolsBundle\Entity\Address;

class LoadAddress extends AbstractFixture implements OrderedFixtureInterface
{

    private $arrayDatas = [
        [
            "18 Avenue Franklin Roosevelt",
            "Paris", "75008", "France"
        ], [
            "19 Avenue Franklin Roosevelt",
            "Paris", "75008", "France"
        ], [
            "20 Avenue Franklin Roosevelt",
            "Paris", "75008", "France"
        ], [
            "21 Avenue Franklin Roosevelt",
            "Paris", "75008", "France"
        ]
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->arrayDatas as $oneAddress) {

            $address = new Address();

            $address
                ->setStreet($oneAddress[0])
                ->setCity($oneAddress[1])
                ->setZipCode($oneAddress[2])
                ->setCountry($oneAddress[3])
            ;

            $manager->persist($address);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 7;
    }
}
