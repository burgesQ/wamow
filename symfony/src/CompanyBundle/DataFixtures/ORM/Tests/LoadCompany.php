<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CompanyBundle\Entity\Company;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadCompany extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $businessPractice = $manager->getRepository('MissionBundle:BusinessPractice')
                    ->findOneBy(array('name' => 'businesspractice.industry'));

        $company = new Company();
        $company->setName('Esso');
        $company->setSize(3);
        $company->setLogo('esso.png');
        $company->setResume('Gasoil');
        $company->setBusinessPractice($businessPractice);

        $manager->persist($company);

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 5;
    }
}
