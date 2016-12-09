<?php

namespace ToolsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ToolsBundle\Entity\Config;

class LoadConfig implements FixtureInterface
{

  public function load(ObjectManager $manager)
  {
      $array = array("nbStep" => 3,
                      "step1" => array('nbMaxTeam' => 10,
                                      'reallocTeam' => 10),
                      "step2" => array('nbMaxTeam' => 3,
                                      'reallocTeam' => 1),
                      "step3" => array('nbMaxTeam' => 1,
                                      'reallocTeam' => 0)
                  );
      $position = 1;
      $json = array();
      $step = 'step'.$position;
      $json["nbStep"] = $array["nbStep"];
      while (isset($array[$step]) == true)
          {
              $json[$step] = array('position' => $position,
                                  'status'    => 0,
                                  'nbMaxTeam' => $array[$step]["nbMaxTeam"],
                                  'reallocTeam' => $array[$step]["reallocTeam"],
                                  'startDate' => null,
                                  'endDate' => null
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
}
