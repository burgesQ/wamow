<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadContractor implements FixtureInterface, ContainerAwareInterface
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

        $contractor = $userManager->createUser();
        $contractor->setUsername('name');
        $contractor->setFirstName('contractor');
        $contractor->setLastName('contractor');
        $contractor->setEmail('contractor@domain.com');
        $contractor->setPlainPassword('password');
        $contractor->setEnabled(true);
        $contractor->setRoles(array('ROLE_CONTRACTOR'));
        $userManager->updateUser($contractor, true);
    }
}
