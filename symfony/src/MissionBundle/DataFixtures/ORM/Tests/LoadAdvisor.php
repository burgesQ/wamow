<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use ToolsBundle\Entity\Address;
use ToolsBundle\Entity\Language;
use MissionBundle\Entity\Mission;
use MissionBundle\Entity\ProfessionalExpertise;
use MissionBundle\Entity\MissionKind;
use MissionBundle\Entity\Step;
use TeamBundle\Entity\Team;

class LoadAdvisor implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $mission = $manager->getRepository('MissionBundle:Mission')
                    ->findOneBy(array('title' => "Fixture Test"));

        $i = $j = 0;
        while ($i < 30)
        {
            $user = $userManager->createUser();
            $user->setUsername('name'.$i);
            $user->setFirstName($i);
            $user->setLastName($i);
            $user->setEmail($i.'email@domain.com');
            $user->setPlainPassword('password');
            $user->setEnabled(true);
            $user->setRoles(array('ROLE_ADVISOR'));
            $userManager->updateUser($user, true);

            $team = new Team(0);
            $team->addUser($user);
            $team->setMission($mission);
            if ($i < 10)
                $team->setStatus(1);
            $manager->persist($team);
            $i++;
        }
        while ($j < 5)
        {
            $user = $userManager->createUser();
            $user->setUsername('name'.$i);
            $user->setFirstName($i);
            $user->setLastName($i);
            $user->setEmail($i.'email@domain.com');
            $user->setPlainPassword('password');
            $user->setEnabled(true);
            $user->setRoles(array('ROLE_CONTRACTOR'));
            $userManager->updateUser($user, true);
            $i++;
            $j++;
        }
        $manager->flush();
    }
}
