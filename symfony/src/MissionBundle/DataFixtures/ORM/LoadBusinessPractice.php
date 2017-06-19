<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\BusinessPractice;

class LoadBusinessPractice extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $names = [
            'businesspractice.construction',
            'businesspractice.energy',
            'businesspractice.finance',
            'businesspractice.hotel',
            'businesspractice.industry',
            'businesspractice.it',
            'businesspractice.media',
            'businesspractice.ngo',
            'businesspractice.public',
            'businesspractice.realestate',
            'businesspractice.retail',
            'businesspractice.services',
            'businesspractice.tourism'
        ];

        foreach ($names as $name) {
            $kind = new BusinessPractice();
            $kind->setName($name);
            $manager->persist($kind);
        }
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }
}
