<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\ProfessionalExpertise;
use MissionBundle\Entity\MissionKind;

class LoadCategory implements FixtureInterface
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

    $names2 = array(
      'Advisory Strategic',
      'Advisory Execution',
      'Advisory Organisation - Transformation',
      'Advisory Business reingeniering',
      'Systems Information CSP',
      'Systems Information MOA',
      'Systems Information MOE',
      'Systems Information TMA',
      'Systems Information Infra structure',
      'Systems Information Migration',
      'Systems Information Implementation',
      'Interim management ',
      'Recruitment ',
      'Change',
      'Coaching',
      'Learning',
      'Audit',
      'Cost killing',
      'Out placement',
      'Certification',
      'Out sourcing',
      'Mediation',
      'Communication',
      'Knowledge management',
      'CRM',
    );

    foreach ($names as $name) {
      $expertise = new ProfessionalExpertise();
      $expertise->setName($name);
      $manager->persist($expertise);
    }
    foreach ($names2 as $name) {
      $kind = new MissionKind();
      $kind->setName($name);
      $manager->persist($kind);
    }
    $manager->flush();
  }
}
