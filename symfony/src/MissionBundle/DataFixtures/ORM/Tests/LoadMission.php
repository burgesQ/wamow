<?php

namespace MissionBundle\DataFixtures\ORM\Tests;

use MissionBundle\Entity\Step;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use MissionBundle\Entity\Mission;
use ToolsBundle\Entity\Address;
use function uniqid;

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
        // get all kind of repo
        $missionKindRepo = $manager->getRepository('MissionBundle:MissionKind');
        $practiceRepo    = $manager->getRepository('MissionBundle:BusinessPractice');
        $proExpRepo      = $manager->getRepository('MissionBundle:ProfessionalExpertise');
        $langRepo        = $manager->getRepository('ToolsBundle:Language');

        // get a Company entity
        $company = $manager->getRepository('CompanyBundle:Company')->findOneBy(['name' => 'Esso']);

        // get contractor one
        $contractorOne = $manager->getRepository('UserBundle:User')->findOneBy(['lastName' => "One"]);

        $i = 1;
        // foreach mission
        foreach ($json as $oneMission) {
            // get all kind of repo

            $mission = new Mission(3, $contractorOne, $company);

            // set mission datas
            $mission
                ->setStatus(Mission::PUBLISHED)
                ->setStatusGenerator(Mission::DONE)
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

            // persist mission
            $manager->persist($mission);
            $manager->flush();

            // set Id's
            $mission->setPublicId(md5(uniqid().$i));
            $this->loadStep($manager, $mission);

            $this->container->get('mission_matching')->setUpPotentialUser($mission);
        }

        // save all that shit
        $manager->flush();
    }

    /**
     * @param ObjectManager                 $manager
     * @param \MissionBundle\Entity\Mission $mission
     */
    private function loadStep($manager, $mission)
    {
        $step  = new Step(10, 10);
        $step2 = new Step(3, 1);
        $step3 = new Step(1, 0);

        $step
            ->setMission($mission)
            ->setUpdateDate(new \DateTime())
            ->setStatus(1)
            ->setPosition(1);
        $step2
            ->setMission($mission)
            ->setUpdateDate(new \DateTime())
            ->setPosition(2);
        $step3
            ->setMission($mission)
            ->setUpdateDate(new \DateTime())
            ->setPosition(3);

        $manager->persist($step);
        $manager->persist($step2);
        $manager->persist($step3);
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
        return 14;
    }
}
