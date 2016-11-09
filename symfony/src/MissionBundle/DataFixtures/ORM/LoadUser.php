<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\ProfessionalExpertise;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
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

        $i = 0;
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
            $i++;
        }
        $j = 0;
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
    }
}
