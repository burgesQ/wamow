<?php

namespace MissionBundle\DataFixtures\ORM\Tests;

use Doctrine\Common\Persistence\ObjectManager;
use CompanyBundle\Entity\Company;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use ToolsBundle\Entity\Address;

class LoadCompany extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $businessPractice = $manager->getRepository('MissionBundle:BusinessPractice')
            ->findOneBy(array('name' => 'businesspractice.industry'));

        $address = new Address();
        $address->setCountry('FR');

        $company = new Company();
        $company
            ->setName('Esso')
            ->setResume('Gasoil')
            ->setBusinessPractice($businessPractice)
            ->setAddress($address)
            ->setLogo('https://upload.wikimedia.org/wikipedia/commons/thumb/2/22/Esso_textlogo.svg/1200px-Esso_textlogo.svg.png')
        ;

        $manager->persist($address);
        $manager->persist($company);

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 11;
    }
}
