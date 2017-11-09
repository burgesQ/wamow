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
        if ($i > $step->getNbMaxUser() || $i == 0)
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.minimum', array(
                        '%limit%' => $step->getNbMaxUser()), 'MissionBundle' ));
        }
        elseif ($i > $step->getReallocUser())// If you want to delete more than you can
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.limit', array("%limit%" => $step->getReallocUser()), 'MissionBundle'));
        }
        elseif ($j - $i <= 0)// If after the deletion there is no user left
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.delete', array(), 'MissionBundle'));
        }
        elseif ($step->getReallocUser() == 0) // If you deleted the maximum of user that you can
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.counter', array(), 'MissionBundle'));
        }
    }
 }
