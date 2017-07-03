<?php

namespace InboxBundle\Services;

use FOS\MessageBundle\MessageBuilder\NewThreadMessageBuilder;
use Symfony\Component\DependencyInjection\Container;
use FOS\MessageBundle\FormType\ReplyMessageFormType;
use InboxBundle\Repository\MessageRepository;
use InboxBundle\Repository\ThreadRepository;
use MissionBundle\Form\ThreadMissionType;
use Symfony\Component\Form\FormInterface;
use InboxBundle\Entity\MessageMetadata;
use MissionBundle\Entity\UserMission;
use MissionBundle\Entity\Mission;
use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityManager;
use InboxBundle\Entity\Message;
use InboxBundle\Entity\Thread;
use UserBundle\Entity\User;

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

        $title = $mission->getTitle() . " : " . $advisor->getId();
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
     * Create the reply form for the thread of a mission
     * Manage new message on the thread
     *
     * @param $thread
     * @param $user
     * @param $request
     *
     * @return ReplyMessageFormType|null
     */
    public function getReplyForm($thread, $user, $request)
    {
        // create form
        /** @var Form $replyForm */
        $replyForm = $this->container->get('fos_message.reply_form.factory')
            ->create($thread);

        // handle request
        if ($replyForm->handleRequest($request)->isSubmitted() && $replyForm->isValid()) {
            // use FOSMessageBundle methode
            $composer = $this->container->get('fos_message.composer');
            $sender   = $this->container->get('fos_message.sender');

            $message = $composer->reply($thread)->setSender($user)
                ->setBody($replyForm->getData()->getBody())->getMessage();
            $sender->send($message);
            $message->setIsReadByParticipant($user, true);
            $this->em->flush();

            // return null to inform the controller
            // to reload the page
            return null;
        }

        // return form
        return $replyForm->createView();
    }

    /**
     * Set a array of array with all data of the view for
     * MissionBundle:show_thread_seeker
     *
     * @param User    $user
     * @param Mission $mission
     * @param         $request
     *
     * @return array|null
     */
    public function setViewContractor($user, $mission, $request)
    {
        // get utilitys
        /** @var MessageRepository $messageRepo */
        $messageRepo = $this->em->getRepository('InboxBundle:Message');
        /** @var ThreadRepository $threadRepo */
        $threadRepo = $this->em->getRepository('InboxBundle:Thread');

        // set empty arrays
        $bigArrayThreads = $threadRepo->findBy(['mission' => $mission]);
        $bigArrayMessage = [];
        $bigArrayUserId  = [];
        $bigArrayRead    = [];

        /** @var FormInterface $form */
        $form = $this->container->get('form.factory')
            ->create(new ThreadMissionType(), $mission);

        // if their is a response
        if ($form->handleRequest($request)->isSubmitted()) {
            /** @var Thread $oneThread */
            foreach ($bigArrayThreads as $oneThread) {
                if ($oneThread->getReply() != null) {
                    // get last meta for copy
                    /** @var MessageMetadata $templateMeta */
                    $templateMeta = $oneThread->getMessages()[0]->getMetadata()[0];

                    // get the sender service
                    $sender = $this->container->get('fos_message.sender');

                    // create the new message
                    $message = new Message();
                    $message->setThread($oneThread);
                    $message->setSender($user);
                    $message->setBody($oneThread->getReply());
                    $this->em->persist($message);
                    $this->em->flush();

                    // create the new messageMetadata
                    $meta = new MessageMetadata();
                    $meta->setMessage($message);
                    $meta->setParticipant($templateMeta->getParticipant());
                    $meta->setIsRead(false);
                    $this->em->persist($meta);
                    $this->em->flush();

                    // link the meta to the message
                    $message->addMetadata($meta);
                    $this->em->flush();

                    // send the message
                    $sender->send($message);
                    $message->setIsReadByParticipant($user, true);

                    // reset the request and the reply data
                    $oneThread->setReply(null);
                    $this->em->flush();

                    // return null to inform the controller to reload the page
                    return null;
                }
            }
        }

        // manage data from the threads
        /** @var Thread $oneThread */
        foreach ($bigArrayThreads as $oneThread) {
            $bigArrayUserId[]  = $oneThread->getUserMission()->getUser()->getId();
            $bigArrayMessage[] = $messageRepo->getMessageForThread($oneThread, $user->getNbLoad());
            $bigArrayRead[]    = $this->updateReadReport($oneThread, $user);
        }

        // return array data with
        // - array of thread for the mission
        // - array of array of message by thread
        // - array of UserId for AnonymousMod
        // - array of bool for the readReport of the last message
        // - form (ThreadMissionForm) to reply to message
        return [
            $bigArrayThreads,
            $bigArrayMessage,
            $bigArrayUserId,
            $bigArrayRead,
            $form->createView()
        ];
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
}
