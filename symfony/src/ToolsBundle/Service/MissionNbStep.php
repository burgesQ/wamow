<?php

namespace ToolsBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

 class MissionNbStep
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
        {
            $this->em = $em;
        }

    public function getMissionConfig()
        {
            $em = $this->em;
            $idConfig = 1;
            $config = $em->getRepository('ToolsBundle:Config')->find($idConfig);
            $config = $config->getValue();
            $array = json_decode($config, true);
            return ($array);
        }

    public function getMissionNbStep()
        {
            $array = $this->getMissionConfig();
            return ($array["nbStep"]);
        }

    public function getMissionStep($position)
        {
            $array = $this->getMissionConfig();
            $step = "step".''.$position.'';
            return ($array[''.$step.'']);
        }
}
