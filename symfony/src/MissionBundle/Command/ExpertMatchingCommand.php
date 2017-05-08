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
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;

use MissionBundle\Entity\UserMission;

class ExpertMatchingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('expert:matching')
            ->setDescription('Match experts with missions.')

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
        $io = new SymfonyStyle($input, $output);
        $io->title('Expert Matching command');

        $em = $this->getContainer()->get('doctrine')->getManager();

        $io->section('Update Matching');
        $missions = $em->getRepository("MissionBundle:Mission")->getExpertMissionsAvailables();
        $progress = new ProgressBar($output, count($missions));
        $progress->start();
        $nbUserMissions = 0;
        $nbNewUserMissions = 0;
        // TODO (?) : clear previous UserMissions (can a matching change..? should we remove "obsolete" ones..?)
        foreach ($missions as $mission) {
            $progress->advance();
            $users = $em->getRepository("MissionBundle:Mission")->findUsersByMission($mission);
            foreach ($users as $user) {
                $userMission = $em->getRepository("MissionBundle:UserMission")->findOneBy(array("user" => $user, "mission" => $mission));
                if (!$userMission) {
                    $nbNewUserMissions++;
                    $userMission = new UserMission($user, $mission);
                }
                $nbUserMissions++;
                $em->persist($userMission);
            }
        }
        $progress->finish();

        $io->newLine(2);
        $io->section('Update Scoring');
        $progress = new ProgressBar($output, count($missions));
        foreach ($missions as $mission) {
            $progress->advance();
            $this->getContainer()->get("scoring")->updateScorings($mission);
        }
        $progress->finish();

        $io->newLine(2);
        $io->section('Update Activated');
        $progress = new ProgressBar($output, count($missions));
        foreach ($missions as $mission) {
            $progress->advance();
            $this->getContainer()->get("scoring")->updateActivated($mission);
        }
        $progress->finish();


        $em->flush();
        $io->newLine(2);
        $io->success($nbUserMissions.' userMissions mis à jour dont '.$nbNewUserMissions.' créés.');

    }

}
