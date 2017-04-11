<?php

namespace MissionBundle\Command;

use MissionBundle\Entity\BusinessPractice;
use MissionBundle\Entity\Mission;
use MissionBundle\Entity\MissionKind;
use MissionBundle\Entity\ProfessionalExpertise;
use MissionBundle\Entity\WorkExperience;
use MissionBundle\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\Translator;
use ToolsBundle\Entity\Address;
use ToolsBundle\Entity\Language;
use UserBundle\Entity\User;
use UserBundle\Repository\UserRepository;

class ExportMissionsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('missions:export')

            // the short description shown while running "php app/console list"
            ->setDescription('Display Mission Data for CSV file.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to display all intresting data from mission for a CSV file.')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var MissionRepository $missionRepo */
        $missionRepo = $this->getContainer()->get('doctrine')
            ->getManager()->getRepository('MissionBundle:Mission');
        /** @var Translator $translator */
        $translator = $this->getContainer()->get('translator');
        $missions = $missionRepo->findAll();

        $output->writeln('Title, Resume, BusinessPractice, ProfessionalExpertise, Remote Work, TypeMission, Languages, Address');
        /** @var Mission $mission */
        foreach ($missions as $mission) {
            $oneMission = '"' . $mission->getTitle() . "\", \"" . $mission->getResume() . "\", \""
                . $translator->trans($mission->getBusinessPractice()->getName(), [], 'MissionBundle', 'en_US') . "\", \""
                . $translator->trans($mission->getProfessionalExpertise()->getName(), [], 'MissionBundle', 'en_US') . "\", \""
                . $mission->getTelecommuting() . "\", \""
                . $translator->trans($mission->getMissionKind()->getName(), [], 'MissionBundle', 'en_US') . "\", \""
                ;
            $i = 0;
            /** @var Language $oneLanguage */
            foreach ($mission->getLanguages() as $oneLanguage) {
                $translated = $translator->trans($oneLanguage->getName(),
                    [], 'tools', 'en_US');
                if ($i) {
                    $oneMission = $oneMission . ", " . $translated;
                } else {
                    $oneMission = $oneMission . $translated;
                }
                $i++;
            }

            /** @var Address $address */
            $address = $mission->getAddress();

            $oneMission = $oneMission . "\", \"" . $address->getStreet() . ' ' . $address->getCity() . $address->getCountry() . '"';

            $output->writeln($oneMission);
        }
    }

}