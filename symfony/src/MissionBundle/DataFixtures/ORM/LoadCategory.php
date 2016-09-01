<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\Language;

class LoadCategory implements FixtureInterface
{

  public function load(ObjectManager $manager)
  {
    $names = array(
      'English',
      'German',
      'French',
      'Italian',
      'Spanish',
      'Portuguese',
      'Russian',
      'Chinese',
      'Japanese',
      'Korean',
      'Arabic'
    );

    foreach ($names as $name) {
      $language = new Language();
      $language->setName($name);
      $manager->persist($language);
    }
    $manager->flush();
  }
}
