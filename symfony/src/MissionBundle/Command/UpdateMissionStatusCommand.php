<?php

namespace MissionBundle\Command;

use MissionBundle\Entity\Mission;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use MissionBundle\Entity\UserMission;

class UpdateMissionStatusCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('mission:update_mission_status_command')
            ->setDescription('Update mission depending on application ending, start date and end date');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $missions        =
            $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('MissionBundle:Mission')
                ->findMissionToUpdate();
        $stepRepo        =
            $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('MissionBundle:Step');
        $userMissionRepo =
            $this->getContainer()
                ->get('doctrine.orm.entity_manager')
                ->getRepository('MissionBundle:UserMission');
        $now             = new \DateTime();

        $progress = new ProgressBar($output, count($missions));

        /** @var \MissionBundle\Entity\Mission $mission */
        foreach ($missions as $mission) {
            /** @var \MissionBundle\Entity\Step $step */
            $step     = $mission->getActualStep();
            /** @var \MissionBundle\Entity\Step $nextStep */
            $nextStep = $stepRepo->findOneBy([
                'mission'  => $mission->getId(),
                'position' => $step->getPosition() + 1
            ]);

            if ($step->getPosition() == 1 && $now >= $mission->getApplicationEnding()) {
                $nbOngoing = count($userMissionRepo->findBy([
                    'mission' => $mission,
                    'status'  => UserMission::SHORTLIST
                ]));
                if ($nextStep && $nbOngoing > 0 && $nbOngoing < $nextStep->getNbMaxUser()) {
                    $step->setStatus(0);
                    $nextStep->setStatus(1);
                    $mission->setNbOngoing($nbOngoing);
                    $progressUser = new ProgressBar($output, count($mission->getUserMission()));
                    /** @var UserMission $userMission */
                    foreach ($mission->getUserMission() as $userMission) {
                        if ($userMission->getStatus() != UserMission::SHORTLIST) {
                            $userMission->setStatus(UserMission::DISMISS);
                        }
                        $progressUser->advance();
                    }
                    $progressUser->finish();
                }
            }
            $progress->advance();
        }
        $progress->finish();
        $this->getContainer()->get('doctrine.orm.entity_manager')->flush();
    }
}
