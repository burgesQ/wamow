<?php

namespace InboxBundle\Controller;

use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use InboxBundle\Entity\Message;
use InboxBundle\Entity\MessageMetadata;

class InboxController extends Controller
{

    /**
     * Display all the thread
     */
    public function showAllThreadAction()
    {
        throw new NotFoundHttpException('Sorry not existing!');
//        if (($user = $this->getUser()) == null)
//        {
//            return new Response($this->get('translator')->trans('thread.error.logged', array(), 'InboxBundle'));
//        }
//
//        $em = $this->getDoctrine()->getRepository('InboxBundle:Thread');
//         $threads = $em->getThreadsByUser($user->getId());
//
//        return $this->render('InboxBundle:Thread:view_all_thread.html.twig', array(
//            'threads' => $threads
//        ));
    }

    /**
     * Display one thread
     *
     * args : id => id of the thread to display
     * args : request => request from replyForm
     */
    public function showThreadAction($id, Request $request)
    {
        throw new NotFoundHttpException('Sorry not existing!');
//        if (($user = $this->getUser()) == null)
//        {
//            return new Response($this->get('translator')->trans('thread.error.logged', array(), 'InboxBundle'));
//        }
//
//        $em = $this->getDoctrine()->getManager();
//        $messageRepo = $em->getRepository('InboxBundle:Message');
//        $thread = $em->getRepository('InboxBundle:Thread')->findOneById($id);
//
//        if ($thread == null)
//        {
//            return new Response($this->get('translator')->trans('thread.error.exist', array(), 'InboxBundle'));
//        }
//
//        foreach ($thread->getMetadata() as $meta)
//        {
//            if ($meta->getParticipant()->getId() == $user->getId())
//            {
//                $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
//                $form->handleRequest($request);
//                if ($form->isValid())
//                {
//                    $serviceComposer = $this->container->get('fos_message.composer');
//                    $response = $serviceComposer->reply($thread)->setSender($user)
//                              ->setBody($form->getData()->getBody())->getMessage();
//                    $sender = $this->container->get('fos_message.sender');
//                    $sender->send($response);
//                }
//
//                $serviceInbox = $this->container->get('inbox.services');
//
//                return $this->render('InboxBundle:Thread:view_thread.html.twig', array(
//                    'user'          => $user,
//                    'thread'        => $thread,
//                    'arrayMessage'  => $messageRepo->getMessageForThread($thread, $user->getNbLoad()),
//                    'read'          => $serviceInbox->updateReadReport($thread, $user, $em),
//                    'form'          => $form->createView()
//                ));
//            }
//        }
//
//        return new Response($this->get('translator') ->trans('thread.error.rights', array(), 'InboxBundle'));
    }

}
