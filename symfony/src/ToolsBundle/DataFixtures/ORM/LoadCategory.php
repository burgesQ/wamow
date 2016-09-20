<?php

namespace ToolsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ToolsBundle\Entity\Language;

class LoadCategory implements FixtureInterface
{

  public function load(ObjectManager $manager)
  {
    $names = array(
      'English',
      'French',
      'German',
      'Chinese',
      'Spanish',
      'Portuguese',
      'Japanese',
      'Arabic',
      'Italian',
      'Korean'
    );

    foreach ($names as $name) {
      $language = new Language();
      $language->setName($name);
      $manager->persist($language);
    }
    $manager->flush();
  }
}
