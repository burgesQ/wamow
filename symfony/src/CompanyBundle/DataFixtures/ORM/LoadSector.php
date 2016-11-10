<?php

namespace CompanyBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CompanyBundle\Entity\Sector;

class LoadSector implements FixtureInterface
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
}
