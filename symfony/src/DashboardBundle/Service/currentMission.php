<?php

namespace DashboardBundle\Service;

 class currentMission
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
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
