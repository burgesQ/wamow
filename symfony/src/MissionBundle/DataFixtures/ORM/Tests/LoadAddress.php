<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ToolsBundle\Entity\Address;

class LoadAddress implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $address = new Address();
        $address->setStreet("18 Avenue Franklin Roosevelt");
        $address->setCity("Paris");
        $address->setZipCode("75008");
        $address->setCountry("France");
        $manager->persist($address);
        $manager->flush();
    }
}
