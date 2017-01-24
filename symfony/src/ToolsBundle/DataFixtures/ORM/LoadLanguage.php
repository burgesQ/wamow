<?php

namespace ToolsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ToolsBundle\Entity\Language;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadLanguage extends AbstractFixture implements OrderedFixtureInterface
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

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 7;
    }
}
