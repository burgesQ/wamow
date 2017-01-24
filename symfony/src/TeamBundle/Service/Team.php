<?php

namespace TeamBundle\Service;

 class Team
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
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
