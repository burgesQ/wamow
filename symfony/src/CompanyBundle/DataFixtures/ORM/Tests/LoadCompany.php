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
        $sector = $manager->getRepository('CompanyBundle:Sector')
                    ->findOneBy(array('name' => 'Energy'));

        $company = new Company();
        $company->setName('Esso');
        $company->setSize(3);
        $company->setLogo('esso.png');
        $company->setResume('Gasoil');
        $company->setSector($sector);

        $manager->persist($company);

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 3;
    }
}
