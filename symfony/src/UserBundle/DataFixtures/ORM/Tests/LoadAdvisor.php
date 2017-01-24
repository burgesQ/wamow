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
use TeamBundle\Entity\Team;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadAdvisor extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $i = 0;
        while ($i < 30)
        {
            $j = $i + 1;
            $advisor = $userManager->createUser();
            $advisor->setFirstName('advisor'.$i);
            $advisor->setLastName('advisor'.$i);
            $advisor->setEmail($i.'advisor@domain.com');
            $advisor->setPlainPassword('password');
            $advisor->setEnabled(true);
            $advisor->setRoles(array('ROLE_ADVISOR'));
            $advisor->setPasswordSet(true);
            $userManager->updateUser($advisor, true);

            $advisor_bis = $userManager->createUser();
            $advisor_bis->setFirstName('advisor'.$j);
            $advisor_bis->setLastName('advisor'.$j);
            $advisor_bis->setEmail($j.'advisor@domain.com');
            $advisor_bis->setPlainPassword('password');
            $advisor_bis->setEnabled(true);
            $advisor_bis->setRoles(array('ROLE_ADVISOR'));
            $advisor_bis->setPasswordSet(true);
            $userManager->updateUser($advisor_bis, true);

            $team = new Team(0, $advisor);
            $team->setMission($mission);
            $team->addUser($advisor_bis);
            $manager->persist($team);
            $i = $i + 2;
        }
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 12;
    }
}
