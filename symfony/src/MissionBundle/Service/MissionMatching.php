<?php

namespace MissionBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use MissionBundle\Repository\MissionRepository;
use MissionBundle\Entity\UserMission;
use Doctrine\ORM\EntityManager;
use UserBundle\Entity\User;

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
        // get the custom repository from Mission
        /** @var MissionRepository $missionRepository */
        $missionRepository = $this->em->getRepository('MissionBundle:Mission');

        // execute a custom magic Query
        return $missionRepository->getMissionByUser($user);
    }

    /**
     * Get all potential user user by a mission
     * Create the entity user_mission if she doesn't exist
     *
     * @param \MissionBundle\Entity\Mission $mission
     * @param bool                          $edit
     */
    public function setUpPotentialUser($mission, $edit = false)
    {
        // get all kind of repo
        /** @var MissionRepository $missionRepo */
        $missionRepo     = $this->em->getRepository('MissionBundle:Mission');
        $userMissionRepo = $this->em->getRepository('MissionBundle:UserMission');
        $userManager     = $this->container->get('fos_user.user_manager');

        // a tmp array
        $newArray = [];

        // get all previous user_mission
        if ($edit) {
            $oldArray = $userMissionRepo->findBy(['mission' => $mission]);
        }
        // get array of user that match the mission
        $userArray = $missionRepo->getUsersByMission($mission, false);

        /** @var User $oneUser */
        foreach ($userArray as $oneUser) {
            if (!($one = $userMissionRepo->findBy(['user' => $oneUser, 'mission' => $mission]))) {
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
                $this->em->flush($mission);

                $newArray[] = $userMission;
            } else {
                $newArray[] = $one;
            }
        }

        // remove user_mission that was on the previous version of the mission and not anymore
        if ($edit) {
            foreach ($oldArray as $oneUserMission) {
                if (!in_array($oneUserMission, $newArray)) {
                    $this->em->remove($oneUserMission);
                }
            }
            // flush removed user_mission
            $this->em->flush();
        }
    }
}