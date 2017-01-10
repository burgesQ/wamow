<?php

namespace MissionBundle\Service;

 class Mission
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function organiseMissions($listMissionsAvailable, $listCurrentMissions)
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

    public function sendDeleteMessageInView($request, $i, $j, $step, $translator)
    {
        if ($i > $step->getNbMaxTeam() || $i == 0)
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.minimum', array(
                        '%limit%' => $step->getNbMaxTeam()), 'MissionBundle' ));
        }
        elseif ($i > $step->getReallocCounter())// If you want to delete more than you can
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.limit', array("%limit%" => $step->getReallocCounter()), 'MissionBundle'));
        }
        elseif ($j - $i <= 0)// If after the deletion there is no team left
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.delete', array(), 'MissionBundle'));
        }
        elseif ($step->getReallocCounter() == 0) // If you deleted the maximum of team that you can
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.counter', array(), 'MissionBundle'));
        }
    }
}
