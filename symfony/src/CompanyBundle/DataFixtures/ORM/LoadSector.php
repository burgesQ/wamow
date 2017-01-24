<?php

namespace CompanyBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CompanyBundle\Entity\Sector;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadSector extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $names = array(
      'Industry',
      'Finance',
      'Retail',
      'Media-Telco / Entertainment',
      'Tourisme',
      'Construction',
      'Real Estate',
      'Food & Beverage / Hotel / Sport / Leisure',
      'Services',
      'Energy',
      'IT',
      'Public / GO',
      'NGO'
    );

    foreach ($names as $name) {
      $sector = new Sector();
      $sector->setName($name);
      $manager->persist($sector);
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
