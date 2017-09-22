<?php

namespace UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Log\InvalidArgumentException;

class GrantPaymentCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('user:grant-payment')
            // the short description shown while running "php app/console list"
            ->setDescription('Grant the payment for 1 year to a given email address')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Usage: sf user:grant-payement email@address')
            // add a missionId argument
            ->addArgument('email', InputArgument::REQUIRED, 'the user email address w/ the oe he register on the palteform')
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
        $io->title('Payment Granter tools');

        $email      = $input->getArgument('email');

        $user = $this->getContainer()->get('doctrine')
            ->getManager()->getRepository('UserBundle:User')
            ->findOneBy(['email' => $email])
        ;

        if ($user) {

            $type = in_array('ROLE_CONTRACTOR', $user->getRoles()) ? ["contractor_plan_v1_price", "CONTRACTOR_PLAN_V1"]
                : ["advisor_plan_v1_price", "ADVISOR_PLAM_V1"];

            $user->setPlanPaymentProvider("stripe");
            $user->setPlanPaymentAmount($this->getContainer()->getParameter($type[0]));
            $user->setPlanType($type[1]);
            $user->setPlanSubscripbedAt(new \DateTime());
            $user->setPlanExpiresAt(new \DateTime("+12 months"));

            $io->success("User successfully marked as subscriber for the next 12 months.");

            $this->getContainer()->get('doctrine')->getManager()->flush();
        } else {
            $io->error("No such user, double-check email address please.");
        }

    }
}
