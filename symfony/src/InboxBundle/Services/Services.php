<?php

namespace InboxBundle\Services;

use FOS\MessageBundle\MessageBuilder\NewThreadMessageBuilder;
use Symfony\Component\DependencyInjection\Container;
use MissionBundle\Entity\UserMission;
use Doctrine\ORM\EntityManager;
use InboxBundle\Entity\Thread;
use UserBundle\Entity\User;
use Swift_Message;

class Services
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Container
     */
    private $container;

    /**
     * MissionMatching constructor.
     *
     * @param Container     $container
     * @param EntityManager $em
     */
    public function __construct(Container $container, EntityManager $em)
    {
        $this->em        = $em;
        $this->container = $container;
    }

    /**
     * Create a thread between the user that pitch and the user that created the mission
     *
     * @param UserMission $userMission
     * @param string      $content
     *
     * @return \InboxBundle\Entity\Thread|object
     */
    public function createThreadPitch($userMission, $content = null)
    {
        if (($thread = $this->em->getRepository('InboxBundle:Thread')
            ->findOneBy(['userMission' => $userMission]))
        ) {
            return $thread;
        }

        $mission  = $userMission->getMission();
        $advisor  = $userMission->getUser();
        $composer = $this->container->get('fos_message.composer');
        $sender   = $this->container->get('fos_message.sender');

        $title = $mission->getTitle() . " : " . $advisor->getId() . rand();
        // create a thread
        /** @var NewThreadMessageBuilder $message */
        $message = $composer->newThread();
        $message->setSubject($title)->setBody($content ? $content : $title);

        // set the advisor as sender
        $message->setSender($advisor);

        // set the contractor as recipient
        $contractor = $mission->getContact();
        $message->addRecipient($contractor);

        // send first message
        $sender->send($message->getMessage());
        $message->getMessage()->setIsReadByParticipant($advisor, true);

        // update the thread data
        /** @var \InboxBundle\Entity\Thread $thread */
        $thread = $this->em->getRepository('InboxBundle:Thread')->findOneBy(['subject' => $title]);

        $thread->setUserMission($userMission);
        $thread->setMission($mission);

        // update the data
        $this->em->flush();

        return $thread;
    }

    /**
     * Update the readReport status and return it
     *
     * @param Thread $thread
     * @param User   $user
     *
     * @return bool
     */
    public function updateReadReport($thread, $user)
    {
        // if no thread
        if ($thread == null) {
            return false;
        }

        // get last message
        $lastMessages = $thread->getMessages()[count($thread->getMessages()) - 1];

        // if user is not the sender; set the message to read
        if ($user !== $lastMessages->getSender()) {
            $lastMessages->setIsReadByParticipant($user, true);
            $this->em->flush();

            return true;
        }

        // else return the status of the read report for another participant
        foreach ($thread->getParticipants() as $participant) {
            if ($participant != $user) {
                return $lastMessages->isReadByParticipant($participant);
            }
        }

        return false;
    }

    /**
     * @param User   $user
     * @param string $content
     * @param Thread $thread
     */
    public function sendMessage($user, $content, $thread)
    {
        $composer = $this->container->get('fos_message.composer');
        $sender   = $this->container->get('fos_message.sender');

        /** @var \FOS\MessageBundle\Entity\Message $message */
        $message = $composer->reply($thread)->setSender($user)->setBody($content)->getMessage();
        $sender->send($message);
        $message->setIsReadByParticipant($user, true);
        $this->em->flush();
    }

    /**
     * @param \MissionBundle\Entity\UserMission $userMission
     * @param \UserBundle\Entity\User           $user
     */
    public function sendProposaleMessage(UserMission $userMission, User $user)
    {
        $composer = $this->container->get('fos_message.composer');
        $sender   = $this->container->get('fos_message.sender');

        /** @var \InboxBundle\Entity\Message$message */
        $message = $composer->reply($userMission->getThread())->setSender(
            $user)->setBody('')->getMessage();
        $message->setUserMissionProposalId($userMission->getId());
        $sender->send($message);
        $message->setIsReadByParticipant($user, true);
        $this->em->flush();

        $contractor = $userMission->getMission()->getContact();

        if ($contractor->getNotification()) {
            $trans = $this->container->get('translator');
            $mail = Swift_Message::newInstance()
                ->setSubject($trans->trans('mails.subject.new_message', [], 'tools'))
                ->setFrom($this->container->getParameter('email_sender'))
                ->setTo($contractor->getEmail())/* put a valid email address there to test */
                ->setBody($this->container->get('templating')->render('Emails/new_message.html.twig', [
                    'f_name'        => $contractor->getFirstName(),
                    'l_name'        => $contractor->getLastName(),
                    'title'         => $userMission->getMission()->getTitle(),
                    'roles'         => 'ROLE_CONTRACTOR',
                    'missionId'     => $userMission->getMission()->getId(),
                    'userMissionId' => $userMission->getId(),
                ]), 'text/html');


            $this->container->get('mailer')->send($mail);
        }
    }
}
