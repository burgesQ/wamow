<?php

namespace UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Psr\Log\InvalidArgumentException;

class ModeUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('user:change-value')
            // the short description shown while running "php app/console list"
            ->setDescription('UserId Var Value')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Usage: sf user:change-value class entityId var value [-lst]')
            // add a missionId argument
            ->addArgument('class', InputArgument::REQUIRED, '')
            ->addArgument('entityId', InputArgument::REQUIRED, '')
            ->addArgument('Var', InputArgument::REQUIRED, '')
            ->addArgument('Value', InputArgument::REQUIRED, '')
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
        $class      = $input->getArgument('class');
        $entityId   = $input->getArgument('entityId');
        $var        = ucwords($input->getArgument('Var'));
        $setter     = 'set' . $var;
        $getter     = 'get' . $var;
        $value      = $input->getArgument('Value');
        $entityRepo = $this->getContainer()->get('doctrine')
            ->getManager()->getRepository($class)
        ;

        $l = false;
        if ($l) {
            // TODO implement __toString()
            switch ($class) {
                case ('UserBundle:User') :
                    foreach ($entityId->findAll() as $user) {
                        $output->writeln($user->getUsername() . ' ' . $user->getId());
                    }
                    exit;
                case ('Mission:UserMission') :
                    foreach ($entityId->findAll() as $userMission) {
                        $output->writeln($userMission->getUser()->getId()
                                         . ' '
                                         . $userMission->getMission()->getId()
                                         . ' '
                                         . $userMission->getId());
                    }
                    exit;

                default:
                    break;
            };
        }

        if (!is_numeric($entityId)) {
            throw new InvalidArgumentException('invalid id');
        }

        $entity = $entityRepo->findOneBy(['id' => $entityId]);

        $output->writeln([
            $setter,
            $value,
            $entity->$getter()
        ]);
        $entity->$setter($value);
        $output->writeln([
            $entity->$getter()
        ]);
        $this->getContainer()->get('doctrine')->getManager()->flush();
    }
}
