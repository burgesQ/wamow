<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\ProfessionalExpertise;

class LoadExpertise implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $names = array(
      'Business Unit Manager',
      'Human Resources',
      'Purchasing',
      'Production',
      'Sales - Business Development',
      'Marketing - Innovation',
      'Communication',
      'Finance & Accounting',
      'Controlling',
      'IT',
      'Customer Service Support',
      'Distribution',
      'Research & Development ',
      'Administrative Management',
      'Operations',
      'Risk management',
      'Quality Control',
      'Asset Management ',
      'Supply Chain',
      'Digital',
      'Law',
      'CSR'
    );

    foreach ($names as $name) {
      $expertise = new ProfessionalExpertise();
      $expertise->setName($name);
      $manager->persist($expertise);
    }
    $manager->flush();
  }
}
