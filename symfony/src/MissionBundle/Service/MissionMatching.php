<?php

namespace MissionBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use MissionBundle\Entity\UserMission;

class MissionMatching
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Container
     */
    private $container;

    /**
     * MissionMatching constructor.
     *
     * @param Container     $container
     * @param EntityManager $em
     */
    public function __construct(Container $container, EntityManager $em)
    {
        $this->em        = $em;
        $this->container = $container;
    }

    /**
     * A function that take a user as argument and return a array of potential mission for that user
     *
     * @param $user
     *
     * @return array
     */
    public function matchMissionByUser($user)
    {

        // get the custome repository from Mission
        $missionRepository = $this->em->getRepository('MissionBundle:Mission');

        // execute a custom magic Query
        return $missionRepository->getMissionByUser($user);
    }

    /**
     * Get all potential user user by a mission
     * Create the entity user_mission if she doesn't exist
     *
     * @param      $mission
     * @param bool $edit
     */
    public function setUpPotentialUser($mission, $edit = false)
    {
        // get all kind of repo
        $missionRepo     = $this->em->getRepository('MissionBundle:Mission');
        $userMissionRepo = $this->em->getRepository('MissionBundle:UserMission');
        $userManager     = $this->container->get('fos_user.user_manager');

        // a tmp array
        $newArray = [];

        // get all previous user_mission
        if ($edit)
            $oldUserMission = $userMissionRepo->findByMission($mission);

        // get array of user that match the mission
        $userArray = $missionRepo->getUsersByMission($mission, false);

        foreach ($userArray as $oneUser) {
            if (!($one = $userMissionRepo->findByUserAndMission($oneUser, $mission))) {

                // create a user_mission entity for user $oneUser
                $userMission = new UserMission($oneUser, $mission);

                // save the entity
                $this->em->persist($userMission);
                $this->em->flush($userMission);

                // add the entity to the user and save the new state
                $oneUser->addUserMission($userMission);
                $userManager->updateUser($oneUser);

                // add the entity to the mission
                $mission->addUserMission($userMission);

                $newArray[] = $userMission;
            } else {
                $newArray[] = $one;
            }
        }

        // remove user_mission that was on the previous version of the mission and not anymore
        if ($edit)
            foreach ($oldUserMission as $oneUserMission)
                if (!in_array($oneUserMission, $newArray))
                    $this->em->remove($oneUserMission);

        // save new state of the mission
        $this->em->flush();
    }

}