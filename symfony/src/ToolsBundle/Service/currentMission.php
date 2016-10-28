<?php

namespace ToolsBundle\Service;

 class currentMission
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function currentMission($userId)
    {
        $em = $this->em;
        $user = $em
          ->getRepository('UserBundle:User')
          ->find($userId)
            ;
        $listTeam = $user->getTeam();
        $i = 0;
        foreach ($listTeam as $team)
        {
            $mission = $team->getMission();
            $array[$i] = $mission;
            $i++;
        }
        return ($array);
    }

    public function remainingMission($listMissionsAvailable, $listCurrentMissions)
    {
        $i = 0;
        $j = 0;
        foreach ($listMissionsAvailable as $mission)
        {
            foreach ($listCurrentMissions as $currentMission)
            {
                if ($mission->getId() == $currentMission->getId())
                {
                    $i = 1;
                }
            }
            if ($i == 0)
            {
                $array[$j] = $mission;
                $j++;
            }
            $i = 0;
        }
        return ($array);
    }
}
