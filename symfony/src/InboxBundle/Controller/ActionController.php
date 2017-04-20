<?php

namespace InboxBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Swift_Message;

class ActionController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \HttpHeaderException
     */
    public function sendMessageAdvisorAction(Request $request)
    {
        $trans = $this->get('translator');
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('Not a xml request');
        } elseif (!($user = $this->getUser())) {
            throw new NotFoundHttpException($trans->trans('error.logged', [], 'tools'));
        } elseif ($user->getRoles()[0] !== 'ROLE_ADVISOR') {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        } elseif (!$request->request->has('id') || !$request->request->has('message')) {
            throw new \HttpHeaderException('Missing id parameters');
        }

        $id  = $request->request->get('id');
        $msg = $request->request->get('message');
        if (!($userMission = $this->getDoctrine()->getRepository('MissionBundle:UserMission')
                ->findOneBy(['id' => $id])) || $userMission->getUser() !== $user) {
            throw new NotFoundHttpException($trans->trans('error.forbidden', [], 'tools'));
        }

        $composer = $this->get('fos_message.composer');
        $thread   = $userMission->getThread();
        $sender   = $this->get('fos_message.sender');
        $message  = $composer->reply($thread)->setSender($user)->setBody($msg)->getMessage();
        $sender->send($message);
        /** @var \UserBundle\Entity\User $contractor */
        $contractor = $userMission->getMission()->getContact();
        if ($contractor->getNotification()) {
            $message = Swift_Message::newInstance()
                ->setSubject($trans->trans('mails.subject.new_message', [], 'tools'))
                ->setFrom($this->container->getParameter('email_sender'))
                ->setTo($contractor->getEmail())/* put a valid email address there to test */
                ->setBody($this->renderView('Emails/new_message.html.twig', [
                    'f_name'        => $contractor->getFirstName(),
                    'l_name'        => $contractor->getLastName(),
                    'title'         => $userMission->getMission()->getTitle(),
                    'roles'         => 'ROLE_CONTRACTOR',
                    'missionId'     => $userMission->getMission()->getId(),
                    'userMissionId' => $userMission->getId()
                ]), 'text/html');
            $this->get('mailer')->send($message);
        }
        if (!($step = $this->getDoctrine()->getRepository('MissionBundle:Step')->findOneBy([
            'mission' => $userMission->getMission(),
            'status'  => 1]))) {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        return $this->render('@Mission/Mission/Advisor/view_thread_advisor.html.twig', [
            'thread'    => $thread,
            'userId'    => $user->getId(),
            'anonymous' => $step->getAnonymousMode()
        ]);
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \HttpHeaderException
     */
    public function sendMessageContractorAction(Request $request)
    {
        $trans = $this->get('translator');
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('Not a xml request');
        } elseif (!($user = $this->getUser())) {
            throw new NotFoundHttpException($trans->trans('error.logged', [], 'tools'));
        } elseif ($user->getRoles()[0] !== 'ROLE_CONTRACTOR') {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        } elseif (!$request->request->has('id') || !$request->request->has('message')) {
            throw new \HttpHeaderException('Missing id parameters');
        }

        $id  = $request->request->get('id');
        $msg = $request->request->get('message');

        if (!($userMission = $this->getDoctrine()->getRepository('MissionBundle:UserMission')
            ->findOneBy(['id' => $id]))) {
            throw new NotFoundHttpException($trans->trans('error.forbidden', [], 'tools'));
        }
        $composer = $this->get('fos_message.composer');
        $thread   = $userMission->getThread();
        $sender   = $this->get('fos_message.sender');
        $message  = $composer->reply($thread)->setSender($user)->setBody($msg)->getMessage();
        $sender->send($message);

        /** @var \UserBundle\Entity\User $advisor */
        $advisor = $userMission->getUser();

        if ($advisor->getNotification()) {
            $message = Swift_Message::newInstance()
                ->setSubject($trans->trans('mails.subject.new_message', [], 'tools'))
                ->setFrom('want@more.work')
                ->setTo($advisor->getEmail())/* put a valid email address there to test */
                ->setBody($this->renderView(':Emails:new_message.html.twig', [
                    'f_name'        => $advisor->getFirstName(),
                    'l_name'        => $advisor->getLastName(),
                    'title'         => $userMission->getMission()->getTitle(),
                    'roles'         => 'ROLE_CONTRACTOR',
                    'missionId'     => $userMission->getMission()->getId(),
                    'userMissionId' => $userMission->getId()
                ]), 'text/html');

            $this->get('mailer')->send($message);
        }

        if (!($step = $this->getDoctrine()->getRepository('MissionBundle:Step')->findOneBy([
            'mission' => $userMission->getMission(),
            'status'  => 1]))) {
            throw new HttpException('Not a xml request');
        }

        return $this->render('@Mission/Mission/Contractor/view_thread_contractor.html.twig', [
            'userMission' => $userMission,
            'userId'      => $user->getId(),
            'anonymous'   => $step->getAnonymousMode()
        ]);
    }
}