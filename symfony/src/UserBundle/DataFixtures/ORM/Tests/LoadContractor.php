<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadContractor extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $company = $manager->getRepository('CompanyBundle:Company')
                    ->findOneBy(array('name' => 'Esso'));

        $userManager = $this->container->get('fos_user.user_manager');

        $i = 0;
        while ($i < 10)
        {
            $contractor = $userManager->createUser();
            $contractor->setFirstName('contractor'.$i);
            $contractor->setLastName('contractor'.$i);
            $contractor->setEmail($i.'contractor@domain.com');
            $contractor->setPlainPassword('password');
            $contractor->setEnabled(true);
            $contractor->setRoles(array('ROLE_CONTRACTOR'));
            $contractor->setPasswordSet(true);
            $contractor->setCompany($company);
            $userManager->updateUser($contractor, true);
            $i++;
        }
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 8;
    }
}
