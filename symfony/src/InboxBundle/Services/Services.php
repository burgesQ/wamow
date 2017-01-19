<?php

namespace InboxBundle\Services;

use InboxBundle\Entity\Message;
use InboxBundle\Entity\MessageMetadata;
use Symfony\Component\HttpFoundation\Request;

use TeamBundle\Form\ThreadTeamType;

class Services
{

    /**
     * Create a thread between
     * the team that pitch and
     * the team that created the mission
     *
     * args : team => team pitched
     * args : mission => mission pitched
     * args : em => global entityManager
     * args : messageComposer => create thread/message
     * args : sender => entity who send
     */
    public function createThreadPitch($team, $mission, $em, $messageComposer, $sender)
    {
        // create a thread
        $title = $mission->getTitle()." : ".$team->getId();
        $threadBuilder = $messageComposer->newThread();
        $threadBuilder->setSubject($title)->setBody($title);

        // set the pitcher as sender
        $user = $team->getUsers()[0];
        $threadBuilder->setSender($user);

        // set each contractor/advisor as a recipient
        $teamContractor = $mission->getTeamContact();
        foreach ($teamContractor->getUsers() as $contractor)
            $threadBuilder->addRecipient($contractor);
        foreach ($team->getUsers() as $advisor)
            if ($advisor != $user)
            $threadBuilder->addRecipient($advisor);

        // send first message
        $sender->send($threadBuilder->getMessage());

        // update the thread data
        $thread = $em->getRepository('InboxBundle:Thread')->findOneBySubject($title);
        $thread->setTeamCreator($team);
        $thread->setMission($mission);
        $em->persist($thread);
        $em->flush($thread);

        // update the teams data
        $team->addThread($thread);
        $teamContractor->addThread($thread);
        $em->persist($team);
        $em->persist($teamContractor);
        $em->flush();
    }

    /**
     * Get the thread of the mission
     * for a Advisor
     *
     * args : threads => array of thread of a mission
     * args : user => user that pitch the mission
     */
    public function setChatboxAdvisor($threads, $user)
    {
        foreach ($threads as $simpleThread)
        {
            foreach ($simpleThread->getParticipants() as $users)
            {
                if ($users == $user)
                {
                    return $simpleThread;
                }
            }
        }

        return null;
    }

    /**
     * Create the reply form
     * for the thread of a mission
     * Manage new message on the thread
     *
     * args : thread => thread in question
     * args : user => user in question
     * args : request => request of the ThredForm
     * args : container => container
     */
    public function getReplyForm($thread, $user, $request, $container)
    {
        // create form
        $replyForm = $container->get('fos_message.reply_form.factory')->create($thread);
        $replyForm->handleRequest($request);

        // handle request
        if ($replyForm->isSubmitted() && $replyForm->isValid())
        {
            // use FOSMessageBundle methode
            $composer = $container->get('fos_message.composer');
            $sender = $container->get('fos_message.sender');
            $message = $composer->reply($thread)->setSender($user)
                ->setBody($replyForm->getData()->getBody())->getMessage();
            $sender->send($message);

            // return null to inform the controller
            // to reload the page
            return null;
        }

        // return form
        return $replyForm->createView();
    }

    /**
     * update the readReport status
     * and return true if the message as been read
     * else return if the participant haveread the message
     */
    public function updateReadReport($thread, $user, $em)
    {
        // if no thread
        if ($thread == null)
            return false;

        // get last message
        $lastMessages = $thread->getMessages()[count($thread->getMessages()) - 1];

        // if user is not the sender; set the message to read
        if ($user != $lastMessages->getSender())
        {
            $lastMessages->setIsReadByParticipant($user, true);
            $em->persist($lastMessages);
            $em->flush();
            return true;
        }

        // else return the status of the read report for another participant
        foreach ($thread->getParticipants() as $participant)
        {
            if ($participant != $user)
            {
                return $lastMessages->isReadByParticipant($participant);
            }
        }
    }

    /**
     * Set a array of array
     * with all data set for the vew of
     * MissionBundle\Ressource\view\Mission\show_thread_seeker
     */
    public function setViewContractor($mission, $context, $request, $container)
    {
        // get utilitys
        $em = $context->getDoctrine()->getManager();
        $messageRepo = $em->getRepository('InboxBundle:Message');
        $teamContractor = $mission->getTeamContact();
        $user = $context->getUser();

        // set empty arrays
        $bigArrayThreads = $teamContractor->getThreads();
        $bigArrayMessage = [];
        $bigArrayTeamName = [];
        $bigArrayRead = [];

        // create form
        $form = $context->createForm(new ThreadTeamType(), $teamContractor);
        $form->handleRequest($request);

        // if their is a response
        if ($form->isSubmitted())
        {
            foreach ($teamContractor->getThreads() as $oneThread)
            {
                if ($oneThread->getReply() != null)
                {
                    // get last meta for copy
                    $templateMeta = $oneThread->getMessages()[0]->getMetadata()[0];

                    // get the sender service
                    $sender = $container->get('fos_message.sender');

                    // create the new message
                    $message = new Message();
                    $message->setThread($oneThread);
                    $message->setSender($user);
                    $message->setBody($oneThread->getReply());
                    $em->persist($message);
                    $em->flush();

                    // create the new messageMetadata
                    $meta = new MessageMetadata();
                    $meta->setMessage($message);
                    $meta->setParticipant($templateMeta->getParticipant());
                    $meta->setIsRead(false);
                    $em->persist($meta);
                    $em->flush();

                    // link the meta to the message
                    $message->addMetadata($meta);
                    $em->persist($message);
                    $em->flush();

                    // send the message
                    $sender->send($message);

                    // reset the request and the reply data
                    $oneThread->setReply(null);
                    $em->persist($oneThread);
                    $em->flush();

                    // return null to inform the controller
                    // to reload the page
                    return null;
                }
            }
         }

        // manage data from the threads
        foreach ($bigArrayThreads as $oneThread)
        {
            $bigArrayTeamName[] = "Team ".$oneThread->getTeamCreator()->getId();
            $bigArrayMessage[] = $messageRepo->getMessageForThread($oneThread, $user->getNbLoad());
            $bigArrayRead[] = $this->updateReadReport($oneThread, $user, $em);
        }

        // return array data with
        // - array of thread for the mission
        // - array of array of message by thread
        // - array of TeamName for AnonymousMod
        // - array of bool for the readReport of the last message
        // - form (ThreadTeamForm) to reply to message
        return [ $bigArrayThreads, $bigArrayMessage, $bigArrayTeamName, $bigArrayRead, $form->createView()];
    }

}
