<?php

namespace MissionBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MissionBundle\Form\MessageMissionFormType;
use Symfony\Component\HttpFoundation\Request;
use MissionBundle\Entity\UserMission;
use MissionBundle\Entity\Mission;

class MissionController extends Controller
{
    /**
     * Display the given Mission
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param                                           $missionId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, $missionId)
    {
        $em    = $this->getDoctrine()->getManager();
        $trans = $this->get('translator');

        if (($user = $this->geTUser()) === null) {
            throw new NotFoundHttpException($trans->trans('error.logged', [], 'tools'));
        } elseif (($mission = $em->getRepository('MissionBundle:Mission')->findOneBy(['id' => $missionId])) === null) {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        } elseif ($mission->getStatus() < Mission::PUBLISHED) {
            throw new NotFoundHttpException($trans->trans('mission.error.available', ['%id' => $missionId], 'tools'));
        }

        $inboxService    = $this->container->get('inbox.services');
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
        $repositoryStep  = $em->getRepository('MissionBundle:Step');

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            if ($user->getCompany() != $mission->getCompany()) {
                throw new NotFoundHttpException($trans->trans('mission.error.wrongcompany', [], 'MissionBundle'));
            }

            $bigArray = $inboxService->setViewContractor($user, $mission, $request);

            // reload the page if needed (new message receive)
            if ($bigArray == null) {
                return $this->redirectToRoute('mission_view', ['missionId' => $missionId]);
            }

            return $this->render('MissionBundle:Mission:view_seeker.html.twig', [
                'user'            => $user,
                'mission'         => $mission,
                'threads'         => $bigArray[0],
                'bigArrayMessage' => $bigArray[1],
                'arrayReplyForm'  => $bigArray[4],
                'step'            => $repositoryStep->findOneBy(['mission' => $mission, 'status' => 1]),
            ]);
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')) {
            if (($url = $this->get('signed_up')->checkIfSignedUp($user->getStatus()))) {

                return $this->redirectToRoute($url);
            }

            if (!($userMission = $userMissionRepo->findOneBy(['user' => $user, 'mission' => $mission]))) {
                throw new NotFoundHttpException();
            }

            $userMissionStatus = $userMission->getStatus();
            $step              = $em->getRepository('MissionBundle:Step')->findOneby([
                'mission' => $mission,
                'status'  => 1
            ]);

            if (count($userMissionRepo->findMoreThanInterested($mission)) >= $step->getNbMaxUser()
                && $userMissionStatus < UserMission::ONGOING) {
                throw new NotFoundHttpException($trans->trans('mission.error.limit_reach', [], 'tools'));
            }

            switch ($userMissionStatus) {
                case (UserMission::NEW && !$user->getPayment()) :
                case (UserMission::INTERESTED) :
                    $form = $this->createForm(MessageMissionFormType::class);
                    if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
                        $inboxService->createThreadPitch($userMission, $form->getData()['text']);
                        $userMission->setStatus(UserMission::ONGOING);
                        $em->flush();
                    }
                    return $this->render('@Mission/Mission/Advisor/mission_interested.html.twig', [
                        'user_mission' => $userMission,
                        'form'         => $form->createView()
                    ]);

                default :
                    throw new NotFoundHttpException('No view for this UserMission status defined (' .
                                                    $userMissionStatus . ')');
            }
        }

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @param $missionId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function interestedAction($missionId)
    {
        $trans = $this->get('translator');
        $em    = $this->getDoctrine()->getManager();

        // Get Check User
        /** @var \UserBundle\Entity\User $user */
        if (($user = $this->getUser()) === null) {
            throw new NotFoundHttpException($trans->trans('error.logged', [], 'tools'));
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            throw new NotFoundHttpException($trans->trans('mission.error.pitch.contractor', [], 'tools'));
        } elseif (($url = $this->get('signed_up')->checkIfSignedUp($user->getStatus()))) {
            return $this->redirectToRoute($url);
        }

        // Get Check Mission
        $missionRepo = $em->getRepository('MissionBundle:Mission');
        if (!($mission = $missionRepo->findOneBy(['id' => $missionId]))
            || $mission->getStatus() !== Mission::PUBLISHED) {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        // Get Check UserMission
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
        $step            = $em->getRepository('MissionBundle:Step')->findOneby([
            'mission' => $mission,
            'status'  => 1
        ]);
        if (!($userMission = $userMissionRepo->findOneby(['mission' => $mission, 'user' => $user]))
            || count($userMissionRepo->findMoreThanInterested($mission)) >= $step->getNbMaxUser()) {
            throw new NotFoundHttpException($trans->trans('mission.error.limit_reach', [], 'tools'));
        }
        switch (($userMissionStatus = $userMission->getStatus())) {
            case (UserMission::NEW) :

                if ($user->getPayment()) {
                    // mark user as interested for the mission
                    $userMission->setStatus(UserMission::INTERESTED)->setInterestedAt(new \DateTime());
                    $em->flush();
                }
//TODO when strip ok; lil thing to passe userMission to interested
                return $this->redirectToRoute('mission_view', [
                    'missionId' => $mission->getId()
                ]);
            case (UserMission::INTERESTED) :
                throw new NotFoundHttpException($trans->trans('mission.error.interested_twice', [], 'tools'));
            default :
                break;
        };
        throw new NotFoundHttpException($trans->trans('error.forbidden', [], 'tools'));
    }

    /*
    **  Give up
    */
    public function giveUpAction($missionId)
    {
        $trans = $this->get('translator');
        $notification = $this->container->get('notification');
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('MissionBundle:Mission')->findOneById($missionId);
        $user = $this->getUser();
        $userMission = $em->getRepository('MissionBundle:UserMission')->findOneBy(array('user' => $user, 'mission' => $mission));
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
            && $mission != null && $mission->getStatus() >= Mission::PUBLISHED && $userMission != null)
        {
            switch ($userMission->getStatus()) {
                case UserMission::GIVEUP:
                    throw new NotFoundHttpException($trans->trans('mission.error.alreadygiveup', array(), 'MissionBundle'));
                    break;
                case UserMission::DELETED:
                case UserMission::REFUSED:
                case UserMission::DISMISS:
                    throw new NotFoundHttpException($trans->trans('mission.error.cantgiveup', array(), 'MissionBundle'));
                    break;
                case UserMission::NEW:
                case UserMission::INTERESTED:
                    $userMission->setStatus(UserMission::REFUSED);
                    $em->persist($userMission);
                    $em->persist($user);
                    $em->flush();
                    return $this->redirectToRoute('dashboard', array());
                    break;
                case UserMission::WIP:
                    $userMission->setStatus(UserMission::GIVEUP);
                    $em->persist($userMission);
                    $em->persist($user);
                    $em->flush();
                    $param = array(
                        'mission' => $mission->getId(),
                        'user'    => $user->getId(),
                    );
                    // Add notification for advisor
                    $notification->new($user, 1, 'notification.expert.mission.giveup', $param);
                    // Add notification for contractors
                    $param = array(
                        'mission' => $mission->getId(),
                        'user'    => $user->getId(),
                    );
                    $contractor = $mission->getContact();
                    $notification->new($contractor, 1, 'notification.seeker.mission.empty', $param);
                    return $this->redirectToRoute('dashboard', array());
                    break;
            }
        }
        throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', array(), 'MissionBundle'));
    }
}
