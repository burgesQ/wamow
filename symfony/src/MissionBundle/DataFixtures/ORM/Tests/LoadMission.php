<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\Mission;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TeamBundle\Entity\Team;

class LoadMission extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;
    private $arrayDatas = [
        [
            "Test Uno",
            "As a worldwide automotive actor, the company develop innovative powertrain solutions to reduce fuel consumption and CO2 emissions without compromising on the pleasure and dynamics of driving. For one of our business unit, we are looking for consultants to make a Global Analysis and Diagnosis of the Sales function, composed of 170 people on 30 countries (France, Germany, China, Japan, India, South-Korea, USA). The Sales function will have to transform its operating model to face new challenges, Externally (i.e. competitors, new technologies and different client expectations) and Internally (i.e. organization and competences). To adress these challenges, in link with the Business Group growth objectives, we would launch : > an analysis of the strenghts and weaknesses of the function, > an assessment to identify its current and future organization included competences in order to i. define the various targets and goals of the function, ii. identify gap between available and required competences iii. define the relevant organization iv. end up with clear recommendations, including action plans reshaping the organization, the training plan and the talent development at large. We are looking for a professional services firm in capacity to conduct the entire project",

            'businesspractice.industry',
            'professionalexpertises.businessunitmanager',
            [false, ''],
            [false, 0],
            true,
            'typemissions.businessreingeniering',
            ['language.english'],
            "18 Avenue Franklin Roosevelt"
        ],
        [
            "Test Deuzio",
            "L’ Association Française des Trésoriers d’Entreprises (AFTE) est une association
            professionnelle qui fédère les personnes intéressées par la gestion de la trésorerie,
            le financement et la gestion des risques financiers. Elle compte 1400 membres
            dont 1000 sont des trésoriers d'entreprises publiques ou privées.
            Ses principales missions sont : le développement de l’expertise du métier de
            trésorier, la promotion des intérêts de la profession et sa visibilité.
            Le Centre de formation de l'AFTE constitue une des principales activités de
            l’association et propose des formations à la pointe des pratiques animées par des
            experts en fonction et répondant aux critères qualités reconnus par les autorités de
            tutelle (qualification OPQF – Certificat FP FFP). Son offre est composée de plus de
            50 formations présentielles, de 6 certificats professionnels et de formations sur
            mesure. Plus de 1000 jours de formations sont dispensés par an.

            Confrontée à la digitalisation croissante de l'offre de formation (demande des
            entreprises, nouvelles générations de trésoriers, 48% du CA des organismes de
            formation est réalisé en e-learning ou blended-learning), le Centre souhaite
            accompagner ce mouvement et digitaliser son offre, en s'appuyant sur le catalogue
            de formations existantes (présentielles) tout en proposant une nouvelle expérience
            (au niveau de ce qui se fait par la concurrence, en terme d'attraction sur les digital
            natives, et permettant d'optimiser l'efficacité des formations).

            Nous recherchons un conseil pour mener à bien ce projet en lien avec les équipes
            internes i. sur la conduite du projet dans son ensemble (budget-timing) ii. sur un
            exemple de conception de scénario type iii. sur les modalités de transformation
            d'une formation présentielle pilote iv. enfin sur l'accompagnement des équipes
            d'AFTE.",

            'businesspractice.services',
            'professionalexpertises.digital',
            [true, 'Paris'],
            [false, 0],
            false,
            'typemissions.learning',
            ['language.french'],
            "19 Avenue Franklin Roosevelt"
        ],
        [
            "Test Tersio",
            "Une mission incroyable apparement",

            'businesspractice.industry',
            'professionalexpertises.salesbusinessdevelopment',
            [false, ''],
            [false, 0],
            true,
            'typemissions.organisationtransformation',
            ['language.english'],
            "20 Avenue Franklin Roosevelt"
        ],
        [
            "Test Quatro",
            "A unbelievable mission",

            'businesspractice.media',
            'professionalexpertises.marketinginnovation',
            [false, ''],
            [false, 0],
            true,
            'typemissions.communication',
            ['language.french'],
            "21 Avenue Franklin Roosevelt"
        ],

    ];

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        // get all kind of repo
        $practiceRepo    = $manager->getRepository('MissionBundle:BusinessPractice');
        $missionKindRepo = $manager->getRepository('MissionBundle:MissionKind');
        $proExpRepo      = $manager->getRepository('MissionBundle:ProfessionalExpertise');
        $langRepo        = $manager->getRepository('ToolsBundle:Language');
        $addressRepo     = $manager->getRepository('ToolsBundle:Address');

        $missionMatchingService  = $this->container->get('mission_matching');

        // get a Company entity
        $company = $manager->getRepository('CompanyBundle:Company')->findOneByName('Esso');

        // get contractor one and two
        $contractorOne = $manager->getRepository('UserBundle:User')->findOneByLastName("One");
        $contractorTwo = $manager->getRepository('UserBundle:User')->findOneByLastName("Two");

        $i = 1;
        foreach ($this->arrayDatas as $oneMission) {

            // create a Contractor Team
            $team = new Team(1, $contractorOne);
            $team->addUser($contractorTwo);
            $team->setStatus(1);

            // persiste and save the new team
            $manager->persist($team);
            $manager->flush();

            // create mission w/ random token
            $i++;
            $i       *= 2;
            $mission = new Mission(3, $team, 1, $i, $company);

            // set mission datas
            $mission->setInternational(0);
            $mission->setStatus(1);

            // set mission deadLines
            $mission->setApplicationEnding(new \DateTime("12-12-2017"));
            $mission->setMissionBeginning(new \DateTime("01-01-2018"));
            $mission->setMissionEnding(new \DateTime("01-01-2019"));

            // set mission title and content
            $mission->setTitle($oneMission[0]);
            $mission->setResume($oneMission[1]);

            // set businessPractice
            $mission->setBusinessPractice($practiceRepo->findOneByName($oneMission[2]));

            // set profesional Expertises
            $mission->setProfessionalExpertise($proExpRepo->findOneByName($oneMission[3]));

            // set remote work (place is address)
            $mission->setTelecommuting($oneMission[4][0]);

            // set fees
            $mission->setBudget($oneMission[5][1]);

            // set Confidentiality
            $mission->setConfidentiality($oneMission[6]);

            // set missionKind
            $mission->setMissionKind($missionKindRepo->findOneByName($oneMission[7]));

            // set language
            foreach ($oneMission[8] as $lang)
                $mission->addLanguage($langRepo->findOneByName($lang));

            // set address
            $mission->setAddress($addressRepo->findOneByStreet($oneMission[9]));

            // persist mission
            $manager->persist($mission);

            // save missions
            $manager->flush();

            // link the mission to the team
            $team->setMission($mission);

            // persist team
            $manager->persist($team);

            $missionMatchingService->setUpPotentialUser($mission);
        }

        // save teamS
        $manager->flush();

    }

    public function getOrder()
    {
        return 13;
    }

}