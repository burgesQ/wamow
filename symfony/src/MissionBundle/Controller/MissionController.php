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
     * @param  integer                                  $missionId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, $missionId)
    {
        $em    = $this->getDoctrine()->getManager();
        $trans = $this->get('translator');

        if (($user = $this->getUser()) === null) {
            throw new NotFoundHttpException($trans->trans('error.logged', [], 'tools'));
        } elseif (($mission = $em->getRepository('MissionBundle:Mission')->findOneBy(['id' => $missionId])) === null) {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        } elseif ($mission->getStatus() !== Mission::PUBLISHED) {
            throw new NotFoundHttpException($trans->trans('error.mission.available', ['%id' => $missionId], 'tools'));
        } elseif (!($step = $em->getRepository('MissionBundle:Step')->findOneBy(['mission' => $mission, 'status' => 1]))) {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            if ($user->getCompany() !== $mission->getCompany()) {
                throw new NotFoundHttpException($trans->trans('error.mission.wrong_company', [], 'tools'));
            }

            switch ($step->getPosition()) {
                case (1) :
                    return $this->render('@Mission/Mission/Contractor/mission_all_advisor.html.twig', [
                        'missionId'             => $missionId,
                        'title'                 => $mission->getTitle(),
                        'certifications'        => $mission->getCertifications(),
                        'missionKinds'          => $mission->getMissionKinds(),
                        'price'                 => $mission->getPrice() * 1000,
                        'professionalExpertise' => $mission->getProfessionalExpertise()->getName(),
                        'applicationEnding'     => $mission->getApplicationEnding(),
                        'missionBeginning'      => $mission->getMissionEnding(),
                        'missionEnding'         => $mission->getMissionEnding(),
                        'address'               => $mission->getAddress(),
                        'languages'             => $mission->getLanguages(),
                        'telecommuting'         => $mission->getTelecommuting(),
                        'updateDate'            => $mission->getUpdateDate(),
                        'resume'                => $mission->getResume(),
                        'userMissions'          => $em->getRepository('MissionBundle:UserMission')->findAllAtLeastThan($mission, UserMission::ONGOING)
                    ]);
                default :
                    throw new NotFoundHttpException('No view for this Mission with the step (' .
                                                    $step->getStatus() . ')');
                    break;
            }
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')) {
            // check if user fully registred
            if (($url = $this->get('signed_up')->checkIfSignedUp($user->getStatus()))) {
                return $this->redirectToRoute($url);
            }
            $userMissionRepo = $em->getRepository('MissionBundle:UserMission');

            if (!($userMission = $userMissionRepo->findOneBy(['user' => $user, 'mission' => $mission]))) {
                throw new NotFoundHttpException();
            }

            $inboxService      = $this->get('inbox.services');
            $userMissionStatus = $userMission->getStatus();

            // if the maximum number of advisor have been reached
            if (count($userMissionRepo->findAllAtLeastThan($mission, UserMission::ONGOING)) >= $step->getNbMaxUser()
                && $userMissionStatus < UserMission::ONGOING) {
                throw new NotFoundHttpException($trans->trans('error.mission.limit_reach', [], 'tools'));
            }

            // return the view in function of uesrMission::Status
            switch ($userMissionStatus) {
                case ($userMissionStatus === UserMission::MATCHED && !$user->getPayment()) :
                case ($userMissionStatus === UserMission::ACTIVATED && !$user->getPayment()) :
                case (UserMission::INTERESTED) :
                    $form = $this->createForm(MessageMissionFormType::class);
                    if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
                        // passe the user_mission to the next step

                        $userMission->setIdForContractor(count($userMissionRepo->findAllAtLeastThan($mission,
                            UserMission::ONGOING)) + 1);
                        $inboxService->createThreadPitch($userMission, $form->getData()['text']);
                        $userMission->setStatus(UserMission::ONGOING);
                        $em->flush();

                        return $this->redirectToRoute('mission_view', ['missionId' => $missionId]);
                    }
                return $this->render('@Mission/Mission/Advisor/mission_interested.html.twig', [
                    'user_mission' => $userMission,
                    'form'         => $form->createView()
                ]);
                case (($userMissionStatus === UserMission::ACTIVATED || $userMissionStatus === UserMission::MATCHED)
                    && $user->getPayment()) :
                    return $this->interestedAction($missionId);
                case (UserMission::ONGOING) :
                case (UserMission::SHORTLIST) :
                    return $this->render('@Mission/Mission/Advisor/mission_to_answer.html.twig', [
                        'user_mission' => $userMission,
                        'userId'       => $userMission->getUser()->getId(),
                        'anonymous'    => $step->getAnonymousMode()
                    ]);
                default :
                    throw new NotFoundHttpException('No view for this UserMission status defined (' .
                                                    $userMissionStatus . ')');
            }
        }
        return $this->redirectToRoute('dashboard');
    }

    /**
     * Mark the user mission as interested
     *
     * @param integer $missionId
     *
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
            throw new NotFoundHttpException($trans->trans('error.mission.pitch.contractor', [], 'tools'));
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
            || count($userMissionRepo->findAllAtLeastThan($mission, UserMission::INTERESTED)) >= $step->getNbMaxUser()) {
            throw new NotFoundHttpException($trans->trans('error.mission.limit_reach', [], 'tools'));
        }
        switch (($userMissionStatus = $userMission->getStatus())) {
            case (UserMission::ACTIVATED) :
            case (UserMission::ONGOING) :
                if ($user->getPayment()) {
                    // mark user as interested for the mission
                    $userMission->setStatus(UserMission::INTERESTED)->setInterestedAt(new \DateTime());
                    $em->flush();
                }
                return $this->redirectToRoute('mission_view', [
                    'missionId' => $mission->getId()
                ]);
            case (UserMission::INTERESTED) :
                throw new NotFoundHttpException($trans->trans('error.mission.interested_twice', [], 'tools'));
            default :
                break;
        };
        throw new NotFoundHttpException($trans->trans('error.forbidden', [], 'tools'));
    }

    /**
     * Mark the mission as ShortListed
     *
     * @param integer $missionId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function shortlistAction($missionId)
    {
        $trans = $this->get('translator');
        $em    = $this->getDoctrine()->getManager();

        // Get Check User
        /** @var \UserBundle\Entity\User $user */
        if (($user = $this->getUser()) === null) {
            throw new NotFoundHttpException($trans->trans('error.logged', [], 'tools'));
        } elseif (!$this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            throw new NotFoundHttpException($trans->trans('error.forbidden', [], 'tools'));
        }

        // Get Check Mission
        $missionRepo = $em->getRepository('MissionBundle:Mission');
        if (!($mission = $missionRepo->findOneBy(['id' => $missionId]))
            || $mission->getStatus() !== Mission::PUBLISHED
            || $user->getCompany() !== $mission->getCompany()) {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        // Get Check Count UserMission
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');

        if (!($step = $em->getRepository('MissionBundle:Step')->findOneby([
            'mission' => $mission, 'status'  => 1]))
            || !($nextStep = $em->getRepository('MissionBundle:Step')->findOneby([
                'mission'  => $mission, 'position' => $step->getPosition() + 1]))
            || count($userMissionRepo->findAllAtLeastThan($mission, UserMission::SHORTLIST)) >= $nextStep->getNbMaxUser()) {
            throw new NotFoundHttpException($trans->trans('error.mission.not_enough', [], 'tools'));
        }

        $step->setStatus(0);
        $nextStep->setStatus(1);
        $em->flush();

        return $this->redirectToRoute('mission_view', ['missionId' => $missionId]);
    }

    /**
     * @param $userMissionId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function answerToAdvisorAction($userMissionId)
    {
        $trans = $this->get('translator');

        if (!($user = $this->getUser())) {
            throw new NotFoundHttpException($trans->trans('error.logged', [], 'tools'));
        } elseif ($user->getRoles()[0] !== 'ROLE_CONTRACTOR') {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        if (!($userMission =  $this->getDoctrine()->getRepository('MissionBundle:UserMission')->findoneBy(['id' => $userMissionId]))
            || $userMission->getMission()->getcompany() !== $user->getcompany()) {
            throw new NotFoundHttpException($trans->trans('error.user_mission.not_found', ['id' => $userMissionId] , 'tools'));
        }

        /** @var \MissionBundle\Entity\Mission $mission */
        $mission = $userMission->getMission();

        if (!($step = $this->getDoctrine()->getRepository('MissionBundle:Step')
            ->findOneBy(['mission' => $mission, 'status' => 1]))) {
            throw new NotFoundHttpException('No step found.');
        }

        return $this->render('@Mission/Mission/Contractor/mission_answer_to_advisor.html.twig', [
            'title'                 => $mission->getTitle(),
            'certifications'        => $mission->getCertifications(),
            'missionKinds'          => $mission->getMissionKinds(),
            'price'                 => $mission->getPrice() * 1000,
            'professionalExpertise' => $mission->getProfessionalExpertise()->getName(),
            'applicationEnding'     => $mission->getApplicationEnding(),
            'missionBeginning'      => $mission->getMissionEnding(),
            'missionEnding'         => $mission->getMissionEnding(),
            'address'               => $mission->getAddress(),
            'languages'             => $mission->getLanguages(),
            'telecommuting'         => $mission->getTelecommuting(),
            'updateDate'            => $mission->getUpdateDate(),
            'resume'                => $mission->getResume(),
            'interested'            => count($this->getDoctrine()->getRepository('MissionBundle:UserMission')
                ->findAllAtLeastThan($mission,UserMission::ONGOING)),
            'shortlisted'           => count($this->getDoctrine()->getRepository('MissionBundle:UserMission')
                ->findAllAtLeastThan($mission,UserMission::SHORTLIST)),
            'userMission'           => $userMission,
            'anonymous'             => $step->getAnonymousMode(),
            'userId'                => $user->getId(),
            'nbAdvisor'             => $userMission->getIdForContractor()
        ]);
    }

    /**
     * @param $missionId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function giveUpAction($missionId)
    {
        $trans        = $this->get('translator');
        $notification = $this->container->get('notification');
        $em           = $this->getDoctrine()->getManager();
        $mission      = $em->getRepository('MissionBundle:Mission')->findOneBy(['id' => $missionId]);
        $user         = $this->getUser();
        $userMission  = $em->getRepository('MissionBundle:UserMission')
            ->findOneBy(['user' => $user, 'mission' => $mission]);

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
            && $mission  && $mission->getStatus() >= Mission::PUBLISHED && $userMission) {
            switch ($userMission->getStatus()) {
                case UserMission::GIVEUP:
                    throw new NotFoundHttpException($trans->trans('error.user_mission.already_giveup', [], 'tools'));
                case UserMission::DELETED:
                case UserMission::ENDDATE:
                case UserMission::DISMISS:
                    throw new NotFoundHttpException($trans->trans('error.user_mission.cant_giveup', [], 'tools'));
                case UserMission::ACTIVATED:
                case UserMission::MATCHED:
                case UserMission::INTERESTED:
                    $userMission->setStatus(UserMission::GIVEUP);
                    $em->flush();
                    return $this->redirectToRoute('dashboard', array());
                case UserMission::ONGOING:
                    $userMission->setStatus(UserMission::GIVEUP);
                    $em->flush();
                    $param = [
                        'mission' => $mission->getId(),
                        'user'    => $user->getId(),
                    ];
                    // Add notification for advisor
                    $notification->new($user, 1, 'notification.expert.mission.giveup', $param);
                    // Add notification for contractors
                    $param = [
                        'mission' => $mission->getId(),
                        'user'    => $user->getId(),
                    ];
                    $contractor = $mission->getContact();
                    $notification->new($contractor, 1, 'notification.seeker.mission.empty', $param);
                    return $this->redirectToRoute('dashboard', []);
            }
        }
        throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', array(), 'MissionBundle'));
    }
}
