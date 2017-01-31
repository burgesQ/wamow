<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\BusinessPractice;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadBusinessPractice extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $names = array(
            'businesspractice.industry',
            'businesspractice.finance',
            'businesspractice.retail',
            'businesspractice.media',
            'businesspractice.tourism',
            'businesspractice.construction',
            'businesspractice.realestate',
            'businesspractice.hotel',
            'businesspractice.services',
            'businesspractice.energy',
            'businesspractice.it',
            'businesspractice.public',
            'businesspractice.ngo',
        );

        foreach ($names as $name) {
            $kind = new BusinessPractice();
            $kind->setName($name);
            $manager->persist($kind);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}
