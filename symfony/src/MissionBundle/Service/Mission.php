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
        foreach ($listMissionsAvailable as $mission)
        {
            foreach ($listCurrentMissions as $currentMission)
            {
                if ($mission->getId() == $currentMission->getId())
                {
                    unset($listMissionsAvailable[array_search($mission, $listMissionsAvailable)]);
                }
            }
        }
        return ($listMissionsAvailable);
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
        elseif ($i > $step->getReallocTeam())// If you want to delete more than you can
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.limit', array("%limit%" => $step->getReallocTeam()), 'MissionBundle'));
        }
        elseif ($j - $i <= 0)// If after the deletion there is no team left
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.delete', array(), 'MissionBundle'));
        }
        elseif ($step->getReallocTeam() == 0) // If you deleted the maximum of team that you can
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.counter', array(), 'MissionBundle'));
        }
    }
}
