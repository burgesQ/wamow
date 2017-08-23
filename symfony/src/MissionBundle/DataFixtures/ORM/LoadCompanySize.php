<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\CompanySize;

class LoadCompanySize extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $names = [
            'company_size.small',
            'company_size.medium',
            'company_size.large'
        ];

        foreach ($names as $name) {
            $continent = new CompanySize($name);
            $manager->persist($continent);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}
