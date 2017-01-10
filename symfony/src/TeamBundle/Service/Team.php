<?php

namespace TeamBundle\Service;

 class Team
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    // Delete teams of the mission
    public function deleteTeams($teams, $step)
    {
        $em = $this->em;
        foreach ($teams as $team)
        {
            $team->setStatus(-1);
            $step->setReallocCounter($step->getReallocCounter() - 1);
            $em->flush($team);
        }
        $em->flush($step);
    }

    // Select Teams for the next step
    public function keepTeams($teams, $step)
    {
        $em = $this->em;
        foreach ($teams as $team)
        {
            $team->setStatus($step->getPosition() + 1);
            $em->flush($team);
        }
        $step->setStatus(0);
        $em->flush($step);
    }

    // Set teams to available
    public function setTeamsAvailable($teams, $position)
    {
        $em = $this->em;
        foreach ($teams as $team)
        {
            $team->setStatus($position);
            $em->flush($team);
        }
    }

    // Check if the contractor own the mission
    public function isContractorOfMission($contractor, $mission)
    {
        $users = $mission->getTeamContact()->getUsers();
        foreach ($users as $user)
        {
            if ($user == $contractor)
                return (true);
        }
        return (false);
    }
}
