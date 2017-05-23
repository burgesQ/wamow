<?php

namespace ToolsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ToolsBundle\Entity\Config;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadConfig extends AbstractFixture implements OrderedFixtureInterface
{

  public function load(ObjectManager $manager)
  {
      $array = [
          "nbStep" => 3,
          "step1" => [
              'nbMaxUser' => 10,
              'reallocUser' => 10,
              'anonymousMode' => 0
          ],
          "step2" => [
              'nbMaxUser' => 3,
              'reallocUser' => 1,
              'anonymousMode' => 1
          ],
          "step3" => [
              'nbMaxUser' => 1,
              'reallocUser' => 0,
              'anonymousMode' => 1
          ]
      ];

      $position = 1;
      $json = array();
      $step = 'step'.$position;
      $json["nbStep"] = $array["nbStep"];
      while (isset($array[$step]) == true)
          {
              $json[$step] = array('position' => $position,
                                   'status'    => 0,
                                   'nbMaxUser' => $array[$step]["nbMaxUser"],
                                   'reallocUser' => $array[$step]["reallocUser"],
                                   'anonymousMode' => $array[$step]["anonymousMode"],
                                   'start' => null,
                                   'end' => null
                                  );
              $position++;
              $step = 'step'.$position;
          }
      $json = json_encode($json);
      $mission_config = new Config();
      $mission_config->setName("mission_config");
      $mission_config->setValue($json);
      $manager->persist($mission_config);
      $manager->flush($mission_config);
  }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}
