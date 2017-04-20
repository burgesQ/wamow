<?php

namespace MissionBundle\DataFixtures\ORM\Tests;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use ToolsBundle\Entity\Address;
use UserBundle\Entity\User;

class LoadContractor extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;
    private $arrayDatas = [
        [
            "Contractor", "One", "contractor@one.com", "contractor@one.com",
            "FR", ['language.french', 'language.english'], "password",
        ], [
            "Contractor", "Two", "contractor@two.com", "contractor@two.com",
            "FR", ['language.french', 'language.english'], "password",
        ], [
            "Contractor", "Three", "contractor@three.com", "contractor@three.com",
            "FR", ['language.french', 'language.english'], "password",
        ], [
            "Contractor", "Four", "contractor@four.com", "contractor@four.com",
            "FR", ['language.french', 'language.english'], "password",
        ], [
            "Contractor", "Five", "contractor@five.com", "contractor@five.com", "
            FR", ['language.french', 'language.english'], "password",
        ], [
            "Contractor", "Six", "contractor@six.com", "contractor@six.com",
            "FR", ['language.french', 'language.english'], "password",
        ], [
            "Contractor", "Seven", "contractor@seven.com", "contractor@seven.com",
            "FR", ['language.french', 'language.english'], "password",
        ], [
            "Contractor", "Eight", "contractor@eight.com", "contractor@eight.com",
            "FR", ['language.french', 'language.english'], "password",
        ], [
            "Contractor", "Nine", "contractor@nine.com", "contractor@nine.com",
            "FR", ['language.french', 'language.english'], "password",
        ]
    ];

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $userRepo    = $manager->getRepository('UserBundle:User');
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

            // set User Company
            $newUser->setCompany($company);

            // save user in db
            $userManager->updateUser($newUser, true);

            $newUser->setPublicId(md5(uniqid() . $newUser->getUsername() . $newUser->getId()));
            $userManager->updateUser($newUser, true);
        }
    }

    public function getOrder()
    {
        return 13;
    }
}
