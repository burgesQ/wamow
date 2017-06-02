<?php

namespace MissionBundle\DataFixtures\ORM\Tests;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use MissionBundle\Entity\Mission;
use ToolsBundle\Entity\Address;
use MissionBundle\Entity\Step;
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
     * @return int
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
            return -1;
        }

        $missionKindRepo = $manager->getRepository('MissionBundle:MissionKind');
        $practiceRepo    = $manager->getRepository('MissionBundle:BusinessPractice');
        $continentRepo   = $manager->getRepository('MissionBundle:Continent');
        $proExpRepo      = $manager->getRepository('MissionBundle:ProfessionalExpertise');
        $langRepo        = $manager->getRepository('ToolsBundle:Language');
        $config          = $manager->getRepository('ToolsBundle:Config')->findAll()[0];

        $southAmerica   = $continentRepo->findOneBy(['name' => 'continent.south_america']);
        $northAmerica   = $continentRepo->findOneBy(['name' => 'continent.north_america']);
        $asia           = $continentRepo->findOneBy(['name' => 'continent.asia']);
        $emea           = $continentRepo->findOneBy(['name' => 'continent.emea']);

        $jsonConfig      = json_decode($config->getValue());

        // get contractor one
        $contractorOne = $manager->getRepository('UserBundle:User')->findOneBy(['lastName' => "One"]);

        $i = 1;
        // foreach mission
        foreach ($json as $oneMission) {
            // get a Company entity
            $company = $manager->getRepository('CompanyBundle:Company')->findOneBy([
                'name' => $oneMission->companyName
            ]);

            // create mission
            $mission = new Mission($jsonConfig->nbStep, $contractorOne, $company);

            // set mission datas
            $mission
                ->setTelecommuting($oneMission->telecommuting)
                ->setStatus(Mission::PUBLISHED)
                ->setConfidentiality($oneMission->confidentiality)
            ;

            // set mission deadLines
            $mission->setApplicationEnding(new \DateTime($oneMission->applicationEnding));
            $mission->setMissionBeginning(new \DateTime($oneMission->missionBeginning));
            $mission->setMissionEnding(new \DateTime($oneMission->missionEnding));

            // set mission title and content
            $mission->setTitle($oneMission->title);
            $mission->setResume($oneMission->resume);

            // set businessPractice
            $mission->setBusinessPractice($practiceRepo->findOneBy(['name' => $oneMission->businessPractice]));

            // set professional Expertise
            $mission->setProfessionalExpertise($proExpRepo->findOneBy(['name' => $oneMission->professionalExpertise]));

            // set missionKind
            foreach ($oneMission->typeMission as $oneKind) {
                $mission->addMissionKind($missionKindRepo->findOneBy(['name' => $oneKind]));
            }

            // set languages
            foreach ($oneMission->languages as $lang) {
                $mission->addLanguage($langRepo->findOneBy(['name' => $lang]));
            }

            // set budget
            $mission->setBudget($oneMission->budget);

            // set address
            $mission->setAddress($this->createAddress($manager, $oneMission->address));
            // set area
            switch ($oneMission) {
                case ($oneMission->northAmerica) :
                    $mission->addContinent($northAmerica);
                case ($oneMission->southAmerica) :
                    $mission->addContinent($southAmerica);
                case ($oneMission->asia) :
                    $mission->addContinent($asia);
                case ($oneMission->emea) :
                    $mission->addContinent($emea);
                default :
                    break;
            };

            $mission->setPublicId(md5(uniqid().$i));
            $this->loadStep($manager, $mission, $jsonConfig);
            $manager->persist($mission);
            $this->container->get('mission_matching')->setUpPotentialUser($mission);
            $i++;
        }

        // bellow we generate fake mission
        if (!($arrayMission = json_decode($string, true))) {
            return -1;
        }

        $nbMission = count($manager->getRepository('MissionBundle:Mission')->findAll());
        $nbUser    = count($manager->getRepository('UserBundle:User')->findAll());
        $fake      = 10;
        $done      = 0;

        while (($nbUser / ($nbMission + $fake)) > 10) {
            $fake++;
        }

        while($done < $fake) {

            $oneMissionId     = array_rand($arrayMission);
            $anotherMissionId = array_rand($arrayMission);
            $oneMission       = $arrayMission[$oneMissionId];
            $anotherMission   = $arrayMission[$anotherMissionId];
            $company         = $manager->getRepository('CompanyBundle:Company')->findOneBy([
                'name' => $anotherMission['companyName']
            ]);
            $mission = new Mission($jsonConfig->nbStep, $contractorOne, $company);

            // set mission datas
            $mission
                ->setTelecommuting($oneMission['telecommuting'])
                ->setStatus(Mission::PUBLISHED)
                ->setConfidentiality($oneMission['confidentiality'])
                ->setTitle('Faker ' . $done)
                ->setResume('Faker ' . $done)
            ;

            // set mission deadLines
            $mission->setApplicationEnding(new \DateTime($oneMission['applicationEnding']));
            $mission->setMissionBeginning(new \DateTime($oneMission['missionBeginning']));
            $mission->setMissionEnding(new \DateTime($oneMission['missionEnding']));

            // set businessPractice
            $mission->setBusinessPractice($practiceRepo->findOneBy(['name' => $anotherMission['businessPractice']]));

            // set professional Expertise
            $mission->setProfessionalExpertise($proExpRepo->findOneBy(['name' => $oneMission['professionalExpertise']]));

            // set missionKind
            foreach ($oneMission['typeMission'] as $oneKind) {
                $mission->addMissionKind($missionKindRepo->findOneBy(['name' => $oneKind]));
            }

            // set languages
            foreach ($anotherMission['languages'] as $lang) {
                $mission->addLanguage($langRepo->findOneBy(['name' => $lang]));
            }

            // set budget
            $mission->setBudget($anotherMission['budget']);

            // set address
            $mission->setAddress($this->createAddress($manager, $oneMission['address'], true));

            // set area
            switch ($oneMission) {
                case $oneMission['northAmerica'] :
                    $mission->addContinent($northAmerica);
                case $oneMission['southAmerica'] :
                    $mission->addContinent($southAmerica);
                case $oneMission['emea'] :
                    $mission->addContinent($emea);
                case $oneMission['asia'] :
                    $mission->addContinent($asia);
            }

            // persist mission
            $this->loadStep($manager, $mission, $jsonConfig);
            $this->container->get('mission_matching')->setUpPotentialUser($mission);

            $manager->persist($mission);

            $done++;
        }

        // save all that shit
        $manager->flush();
        return 0;
    }

    /**
     * @param ObjectManager $manager
     * @param               $oneAddress
     * @param bool          $array
     *
     * @return \ToolsBundle\Entity\Address
     */
    private function createAddress($manager, $oneAddress, $array = false)
    {
        $address = new Address();

        if (!$array) {
            $address->setCity($oneAddress->city)->setCountry($oneAddress->country);
        } else {
            $address->setCity($oneAddress['city'])->setCountry($oneAddress['country']);
        }
        $manager->persist($address);

        return $address;
    }

    /**
     * @param ObjectManager                 $manager
     * @param \MissionBundle\Entity\Mission $mission
     * @param                               $jsonConfig
     */
    private function loadStep($manager, $mission, $jsonConfig)
    {
        $i = 0;
        while ($i < $jsonConfig->nbStep) {
            $i++;
            $step     = 'step' . $i;
            $jsonStep = $jsonConfig->$step;
            $step     = new Step($jsonStep->nbMaxUser, $jsonStep->reallocUser);
            $step
                ->setMission($mission)
                ->setUpdateDate(new \DateTime())
                ->setPosition($i)
                ->setAnonymousMode($jsonStep->anonymousMode)
            ;

            if ($i == 1) {
                $step->setStatus(1);
            }

            $manager->persist($step);
        }
    }

    public function getOrder()
    {
        return 14;
    }
}
