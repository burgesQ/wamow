<?php

namespace MissionBundle\DataFixtures\ORM\Tests;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use ToolsBundle\Entity\Address;
use UserBundle\Entity\User;

class LoadUtils extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;
    private $arrayDatas = [
        [
            "WantMoreWork", "WamoW", "wamow@wamow.co", "wamow@wamow.co",
            "FR", ['language.french', 'language.english', 'language.german'], "password",
        ]
            ];

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $langRepo    = $manager->getRepository('ToolsBundle:Language');
        $company     = $manager->getRepository('CompanyBundle:Company')->findOneByName('Esso');

        foreach ($this->arrayDatas as $oneData) {

            // create Entity with fName, lName, email, country
            $newUser = $userManager->createUser();

            $newUser
                ->setFirstName($oneData[0])
                ->setLastName($oneData[1])
                ->setEmail($oneData[2])
                ->setEmergencyEmail($oneData[3])
            ;

            $address = new Address();
            $address->setCountry($oneData[4]);
            $newUser->addAddress($address);

            // set languages
            foreach ($oneData[5] as $oneLang)
                $newUser->addLanguage($langRepo->findOneByName($oneLang));

            // set password and status
            $newUser
                ->setPlainPassword($oneData[6])
                ->setRoles(['ROLE_CONTRACTOR'])
                ->setEnabled(true)
                ->setStatus(User::REGISTER_NO_STEP);
//
//            // set User Company
//            $newUser->setCompany($company);

            // save user in db
            $userManager->updateUser($newUser, true);
        }
    }

    public function getOrder()
    {
        return 14;
    }
}
