<?php

namespace MissionBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use MissionBundle\Entity\Mission;
use ToolsBundle\Entity\Address;

class LoadMission extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $fileName = 'LoadMission.json';
        $finder   = new Finder();

        // get all file in current DIR
        $finder->files()->in(__DIR__);

        // get content from the mission json file
        foreach ($finder as $file) {
            if (!strcmp($file->getRelativePathname(), $fileName)) {
                $string = file_get_contents($file->getRealPath());
            }
        }

        // if nothing was found exit
        if (empty($string) || !($json = json_decode($string))) {
            exit;
        }

        $missionKindRepo = $manager->getRepository('MissionBundle:MissionKind');
        $practiceRepo    = $manager->getRepository('MissionBundle:BusinessPractice');
        $missionRepo     = $manager->getRepository('MissionBundle:Mission');
        $proExpRepo      = $manager->getRepository('MissionBundle:ProfessionalExpertise');
        $langRepo        = $manager->getRepository('ToolsBundle:Language');

        // get a Company entity
        $company = $manager->getRepository('CompanyBundle:Company')->findOneBy(['name' => 'Esso']);

        // get contractor one
        $contractorOne = $manager->getRepository('UserBundle:User')->findOneBy(['lastName' => "One"]);

        $i = 42;
        // foreach mission
        foreach ($json as $oneMission) {
            // get all kind of repo

            // create mission w/ random token
            while ($i++ && $missionRepo->findOneBy(['token' => $i]));
            $mission = new Mission(3, $contractorOne, $i, $company);

            // set mission datas
            $mission
                ->setInternational(false)
                ->setStatus(1)
                ->setConfidentiality(true)
            ;

            // set mission deadLines
            $mission->setApplicationEnding(new \DateTime("01-01-2018"));
            $mission->setMissionBeginning(new \DateTime("01-01-2019"));
            $mission->setMissionEnding(new \DateTime("01-01-2020"));

            // set mission title and content
            $mission->setTitle($oneMission->title);
            $mission->setResume($oneMission->resume);

            // set businessPractice
            $mission->setBusinessPractice($practiceRepo->findOneBy(['name' => $oneMission->businessPractice]));

            // set professional Expertise
            $mission->setProfessionalExpertise($proExpRepo->findOneBy(['name' => $oneMission->professionalExpertise]));

            // set remote work (place is address)
            $mission->setTelecommuting($oneMission->remoteWork);

            // set missionKind
            foreach ($oneMission->typeMission as $oneKind) {
                $mission->addMissionKind($missionKindRepo->findOneBy(['name' => $oneKind]));
            }

            // set languages
            foreach ($oneMission->languages as $lang)
                $mission->addLanguage($langRepo->findOneBy(['name' => $lang]));

            // set budget
            $mission->setBudget(10);

            // set address
            $mission->setAddress($this->createAddress($manager, $oneMission->address));

            // set area
            $mission->setNorthAmerica(false);
            $mission->setSouthAmerica(false);
            $mission->setAsia(false);
            $mission->setEmea(false);

            // persist mission
            $manager->persist($mission);
            $manager->flush();
            $manager->flush();

            $this->container->get('mission_matching')->setUpPotentialUser($mission);
        }

        // save all that shit
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param               $oneAddress
     *
     * @return Address
     */
    private function createAddress($manager, $oneAddress)
    {
        $address = new Address();

        $address
            ->setCity($oneAddress->city)
            ->setCountry($oneAddress->country)
        ;

        $manager->persist($address);
        $manager->flush();

        return $address;
    }

    public function getOrder()
    {
        return 11;
    }
}
