<?php

namespace ToolsBundle\Service;

 class ConfigMission
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    // Get configuration of missions
    public function getMissionConfig()
    {
        $em = $this->em;
        $config = $em->getRepository('ToolsBundle:Config')
                     ->findOneBy(array('name' => 'mission_config'));
        $config = $config->getValue();
        $array = json_decode($config, true);
        return ($array);
    }

    // Get number of step of mission
    public function getMissionNbStep()
    {
        $array = $this->getMissionConfig();
        return ($array["nbStep"]);
    }

    // Get configuration of specific step
    public function getStepConfig($position)
    {
        $array = $this->getMissionConfig();
        $step = "step".''.$position.'';
        return ($array[''.$step.'']);
    }
}
