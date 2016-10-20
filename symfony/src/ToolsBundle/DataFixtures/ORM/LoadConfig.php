<?php

namespace ToolsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ToolsBundle\Entity\Config;

class LoadConfig implements FixtureInterface
{

  public function load(ObjectManager $manager)
  {
      $array = array("nbStep" => 4,
                      "step0" => array('nbMaxTeam' => 10,
                                      'reallocTeam' => 10),
                      "step1" => array('nbMaxTeam' => 10,
                                      'reallocTeam' => 10),
                      "step2" => array('nbMaxTeam' => 3,
                                      'reallocTeam' => 1),
                      "step3" => array('nbMaxTeam' => 1,
                                      'reallocTeam' => 0)
                  );
      $nbStep = 0;
      $json = array();
      $step = 'step'.$nbStep;
      $json["nbStep"] = $array["nbStep"];
      while (isset($array[$step]) == true)
          {
              $json[$step] = array('position' => $nbStep,
                                  'status'    => 0,
                                  'nbMaxTeam' => $array[$step]["nbMaxTeam"],
                                  'reallocTeam' => $array[$step]["reallocTeam"],
                                  'startDate' => null,
                                  'endDate' => null
                                  );
              $nbStep++;
              $step = 'step'.$nbStep;
          }
      $json = json_encode($json);
      $mission_config = new Config();
      $mission_config->setName("mission_config");
      $mission_config->setValue($json);
      $manager->persist($mission_config);
      $manager->flush($mission_config);
  }
}
