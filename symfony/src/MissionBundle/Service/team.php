<?php

namespace MissionBundle\Service;

 class Team
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function sendDeleteMessageInView($request, $i, $j, $step, $translator)
    {
        if ($i > $step->getNbMaxTeam() || $i == 0)
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.minimum', array(
                        '%limit%' => $step->getNbMaxTeam()), 'MissionBundle' ))
                ;

        }
        elseif ($i > $step->getReallocCounter())// If you want to delete more than you can
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.limit', array("%limit%" => $step->getReallocCounter()), 'MissionBundle'))
                ;
        }
        elseif ($j - $i <= 0)// If after the deletion there is no team left
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.delete', array(), 'MissionBundle'))
                ;
        }
        elseif ($step->getReallocCounter() == 0) // If you deleted the maximum of team that you can
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $translator->trans('mission.selection.counter', array(), 'MissionBundle'))
                ;
        }
    }

    public function setDeletedTeam($deletedTeam, $step)
    {
        $em = $this->em;
        foreach ($deletedTeam as $team)
        {
            $team->setStatus(-1);
            if ($step->getPosition() == 1) {
                $step->setReallocCounter($step->getReallocCounter() - 1);
                $em->flush($step);
            }
            $em->flush($team);
        }
    }

    public function setSaveTeamStatus($chosenTeam, $step)
    {
        $em = $this->em;
        foreach ($chosenTeam as $team)
        {
            $team->setStatus($step->getPosition() + 1);
            $em->flush($team);
        }
        $step->setStatus(0);
        $em->flush($step);
    }

    public function setTeamsAvailables($teamToChoose, $position)
    {
        $em = $this->em;
        foreach ($teamToChoose as $team)
        {
            $team->setStatus($position);
            $em->flush($team);
        }
    }
}
