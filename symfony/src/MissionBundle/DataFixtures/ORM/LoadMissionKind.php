<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\MissionKind;

class LoadMissionKind implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $names = array(
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
      $kind = new MissionKind();
      $kind->setName($name);
      $manager->persist($kind);
    }
    $manager->flush();
  }
}
