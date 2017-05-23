<?php

namespace MissionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetUserForMissionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('mission:get-user')

            // the short description shown while running "php app/console list"
            ->setDescription('Get potential user for a mission.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to get a list of user that match the mission pass as argument')

            // add a missionId argument
            ->addArgument('missionId', InputArgument::REQUIRED, 'The id of the the mission.')
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
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Mission matching test',
            '=====================================================================',
            '',
            'Mission Id                : '.$input->getArgument('missionId'),
        ]);

        $missionRepo = $this->getContainer()->get('doctrine')
            ->getManager()->getRepository('MissionBundle:Mission');

        $mission = $missionRepo->findOneBy([ 'id' => $input->getArgument('missionId')]);

        $output->writeln([
            "",
            '============================= SPECIFICATION ===========================',
            '',
            'Business Practice (b)     : '.$mission->getBusinessPractice()->getName(),
            'Profesional Expertise (p) : '.$mission->getProfessionalExpertise()->getName(),
            'Mission kind (k)          : '
        ]);

        foreach ($mission->getMissionKinds() as $oneMissionKind) {
            $output->writeln(['                           - ' . $oneMissionKind->getName()]);
        }

        $output->writeln([
            'And Speak (l)             : '
            ]);

        foreach ($mission->getLanguages() as $oneLanguage) {
            $output->writeln(' - ' . $oneLanguage->getName());
        }

        $users = $missionRepo->getUsersByMission($mission, true, true);

        $output->writeln([
            $users[1],
            '=============================    RESULT   ===========================',
            ''
        ]);

        foreach ($users[0] as $oneUser) {
            $output->writeln(dump($oneUser->getEmail()).$oneUser->getId());
        }
        $output->writeln('Well, their is only '.count($users[0]).' user that match the mission '.$mission->getTitle());
    }
}
