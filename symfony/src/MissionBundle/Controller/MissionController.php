<?php

namespace MissionBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MissionBundle\Form\MessageMissionFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use ToolsBundle\Form\ProposalFromType;
use MissionBundle\Entity\UserMission;
use MissionBundle\Entity\Mission;
use ToolsBundle\Entity\Proposal;

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
        $em              = $this->getDoctrine()->getManager();
        $trans           = $this->get('translator');
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');

        // check all kind off stuffs
        if (($user = $this->getUser()) === null) {
            throw $this->createNotFoundException($trans->trans('error.logged', [], 'tools'));
        } elseif (($mission = $em->getRepository('MissionBundle:Mission')->findOneBy(['id' => $missionId])) === null) {
            throw $this->createNotFoundException($trans->trans('error.mission.not_found', [], 'tools'));
        } elseif ($mission->getStatus() !== Mission::PUBLISHED) {
            throw $this->createNotFoundException($trans->trans('error.mission.available', ['%id' => $missionId], 'tools'));
        } elseif (!($step = $em->getRepository('MissionBundle:Step')->findOneBy(['mission' => $mission, 'status' => 1]))) {
            throw $this->createNotFoundException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        // if user is a contractor
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            // check if contractor is in the same company as the mission
            if ($user->getCompany() !== $mission->getCompany()) {
                throw $this->createNotFoundException($trans->trans('error.mission.wrong_company', [], 'tools'));
            }

            $nextMission = $this->getDoctrine()->getRepository('MissionBundle:Mission')
                ->findNextMission($missionId, $mission->getCompany());
            $prevMission = $this->getDoctrine()->getRepository('MissionBundle:Mission')
                ->findPrevMission($missionId, $mission->getCompany());

            switch ($step->getPosition()) {
                // if mission is step 1
                case (1) :

                    $userMissions = $userMissionRepo->findAllAtLeastThan($mission, UserMission::ONGOING, 5);

                    return $this->render('@Mission/Mission/Contractor/mission_all_advisor.html.twig', [
                        'mission'      => $mission,
                        'interested'   => count($userMissionRepo->findAllAtLeastThan($mission, UserMission::ONGOING)),
                        'shortlisted'  => count($userMissionRepo->findAllAtLeastThan($mission, UserMission::SHORTLIST)),
                        'userMissions' => $userMissions,
                        'prevMission'  => $prevMission,
                        'nextMission'  => $nextMission,
                        'user'         => $this->getUser(),
                        'selected'     => count($userMissionRepo->findAllAtLeastThan($mission, UserMission::SHORTLIST))
                    ]);
                // if mission id step 2
                case (2) :
                    $userMissions = $userMissionRepo->findAllAtLeastThan($mission, UserMission::SHORTLIST);
                    $nbProposale  = 0;
                    /** @var UserMission $userMission */
                    foreach ($userMissions as $userMission) {
                        if (!$userMission->getThread()->getProposals()->isEmpty()) {
                            $nbProposale++;
                        }
                    }

                    return $this->render('@Mission/Mission/Contractor/mission_shortlist.html.twig', [
                        'mission'      => $mission,
                        'shortlisted'  => count($userMissions),
                        'userMissions' => $userMissions,
                        'nbProposale'  => $nbProposale,
                        'prevMission'  => $prevMission,
                        'nextMission'  => $nextMission,
                        'user'         => $this->getUser()
                    ]);
                // if mission is step 3
                case (3) :
                    // check if at least on userMission is finalist
                    if (!($userMission = $userMissionRepo->findOneBy(['mission' => $mission, 'status'  => UserMission::FINALIST]))) {
                        throw $this->createNotFoundException($trans->trans('error.mission.finalist.no_advisor', [], 'tools'));
                    }

                    return $this->redirectToRoute('mission_answer_to_advisor', [
                        'userMissionId' => $userMission->getId()
                    ]);
                default :
                    throw $this->createNotFoundException('No view for this Mission with the step ' .
                        $step->getPosition());
            }
            // if user is a advisor
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')) {
            // check if user fully registred
            if (($url = $this->get('signed_up')->checkIfSignedUp($user->getStatus()))) {
                return $this->redirectToRoute($url);
            }
            /** @var \UserBundle\Entity\User $user */
            $user = $this->getUser();
            if ($user->getPlanExpiresAt() && $user->getPlanExpiresAt() < new \DateTime()) {
                return new Response("Your subscription expired on " . $user->getPlanExpiresAt()->format('Y-m-d H:i:s'));
            }

            // get associated userMission
            /** @var UserMission $userMission */
            if (!($userMission = $userMissionRepo->findOneBy(['user' => $user, 'mission' => $mission]))) {
                throw $this->createNotFoundException();
            }

            // get all kind of data / services
            $messageService    = $this->get('fos_message.message_reader');
            $inboxService      = $this->get('inbox.services');
            $userMissionStatus = $userMission->getStatus();

            // if the maximum number of advisor have been reached
            if (count($userMissionRepo->findAllAtLeastThan($mission, UserMission::ONGOING)) >= $step->getNbMaxUser()
                && $userMissionStatus < UserMission::ONGOING) {
                throw $this->createNotFoundException($trans->trans('error.mission.limit_reach', [], 'tools'));
            }

            // return the view in function of uesrMission::Status
            switch ($userMissionStatus) {
                // user haven't send any message
                case ($userMissionStatus === UserMission::MATCHED && !$user->getPayment()) :
                case (UserMission::INTERESTED) :
                    $form = $this->createForm(MessageMissionFormType::class);
                    if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
                        $userMission->setIdForContractor(count($userMissionRepo->findAllAtLeastThan($mission, UserMission::ONGOING)) +
                            1);
                        $inboxService->createThreadPitch($userMission, $form->getData()['text']);
                        $userMission->setStatus(UserMission::ONGOING);
                        $user->setScoringBonus($user->getScoringBonus() -
                            $this->container->getParameter("scoring_weight.user_subscribe"));
                        $em->flush();

                        return $this->redirectToRoute('mission_view', ['missionId' => $missionId]);
                    }

                    return $this->render('@Mission/Mission/Advisor/mission_interested.html.twig', [
                        'user_mission'         => $userMission,
                        'form'                 => $form->createView(),
                        'stripePublishableKey' => $this->container->getParameter("stripe_publishable_key"),
                        'matched'              => count($userMissionRepo->findAllAtLeastThan($mission,
                            UserMission::ONGOING))
                    ]);
                // user have subscribe, he can send a message
                case ($userMissionStatus === UserMission::MATCHED && $user->getPayment()) :
                    return $this->interestedAction($missionId);
                // user already have sent a message; the mission isn't shortlisted
                case (UserMission::ONGOING) :
                    $messageService->markAsRead($userMission->getThread()->getLastMessage());
                    $em->flush();

                    return $this->render('@Mission/Mission/Advisor/mission_to_answer.html.twig', [
                        'user_mission' => $userMission,
                        'userId'       => $userMission->getUser()->getId(),
                        'step'         => $step
                    ]);
                // user and mission have been [shortlisted|finalised]
                case (UserMission::SHORTLIST) :
                case (UserMission::FINALIST) :
                    $messageService->markAsRead($userMission->getThread()->getLastMessage());
                    $em->flush();
                    $proposal = new Proposal();
                    $form     = $this->createForm(ProposalFromType::class, $proposal)->handleRequest($request);
                    if ($form->isSubmitted() && $form->isValid() && $form->getData()->getFile() !== null) {

                        $proposal->setThread($userMission->getThread());
                        $em->persist($proposal);
                        $em->flush();

                        $this->get('inbox.services')->sendProposaleMessage($userMission, $user);

                        return $this->redirectToRoute('mission_view', ['missionId' => $missionId]);
                    }

                    $em->flush();

                    return $this->render('@Mission/Mission/Advisor/mission_to_answer.html.twig', [
                        'user_mission' => $userMission,
                        'userId'       => $userMission->getUser()->getId(),
                        'step'         => $step,
                        'form'         => $form->createView()
                    ]);
                default :
                    throw $this->createNotFoundException('No view for this UserMission status defined (' .
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
            throw $this->createNotFoundException($trans->trans('error.logged', [], 'tools'));
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            throw $this->createNotFoundException($trans->trans('error.mission.pitch.contractor', [], 'tools'));
        } elseif (($url = $this->get('signed_up')->checkIfSignedUp($user->getStatus()))) {
            return $this->redirectToRoute($url);
        }

        // Get Check Mission
        $missionRepo = $em->getRepository('MissionBundle:Mission');
        if (!($mission = $missionRepo->findOneBy(['id' => $missionId]))
            || $mission->getStatus() !== Mission::PUBLISHED) {
            throw $this->createNotFoundException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        // Get Check UserMission
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
        $step            = $em->getRepository('MissionBundle:Step')->findOneby(['mission' => $mission, 'status' => 1]);
        if (!($userMission = $userMissionRepo->findOneby(['mission' => $mission, 'user' => $user]))
            || count($userMissionRepo->findAllAtLeastThan($mission, UserMission::INTERESTED)) >= $step->getNbMaxUser()) {
            throw $this->createNotFoundException($trans->trans('error.mission.limit_reach', [], 'tools'));
        }
        switch (($userMissionStatus = $userMission->getStatus())) {
            case (UserMission::MATCHED) :
                if ($user->getPayment()) {
                    // mark user as interested for the mission
                    $userMission->setStatus(UserMission::INTERESTED)->setInterestedAt(new \DateTime());
                    $mission->setNbOngoing($mission->getNbOngoing() + 1);
                    $em->flush();
                }

                return $this->redirectToRoute('mission_view', [
                    'missionId' => $mission->getId()
                ]);
            case (UserMission::INTERESTED) :
                throw $this->createNotFoundException($trans->trans('error.mission.interested_twice', [], 'tools'));
            default :
                break;
        };
        throw $this->createNotFoundException($trans->trans('error.forbidden', [], 'tools'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param                                           $userMissionId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \HttpHeaderException
     */
    public function answerToAdvisorAction(Request $request, $userMissionId)
    {
        $trans = $this->get('translator');

        // check user auth
        if (!($user = $this->getUser())) {
            throw new NotFoundHttpException($trans->trans('error.logged', [], 'tools'));
        } elseif ($user->getRoles()[0] !== 'ROLE_CONTRACTOR') {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        // get/check associated userMission
        /** @var UserMission $userMission */
        if (!($userMission =
                $this->getDoctrine()->getRepository('MissionBundle:UserMission')->findOneBy(['id' => $userMissionId]))
            || $userMission->getMission()->getCompany() !== $user->getcompany()) {
            throw new NotFoundHttpException($trans->trans('error.user_mission.not_found', ['id' => $userMissionId], 'tools'));
        }

        // get mission and step
        /** @var \MissionBundle\Entity\Mission $mission */
        $mission = $userMission->getMission();
        /** @var \MissionBundle\Entity\Step $step */
        if (!($step =
            $this->getDoctrine()->getRepository('MissionBundle:Step')->findOneBy(['mission' => $mission, 'status'  => 1]))) {
            throw new \HttpHeaderException('No next step');
        }

        $messageService = $this->get('fos_message.message_reader');
        switch ($step->getPosition()) {
            case (1) :
            case (2) :
            case (3) :
                $messageService->markAsRead($userMission->getThread()->getLastMessage());
                $this->getDoctrine()->getManager()->flush();
                $userMissions =
                    $this->getDoctrine()
                        ->getRepository('MissionBundle:UserMission')
                        ->findAllAtLeastThan($mission, UserMission::SHORTLIST);
                $nbProposale  = 0;
                /** @var UserMission $oneUserMission */
                foreach ($userMissions as $oneUserMission) {
                    if (!$oneUserMission->getThread()->getProposals()->isEmpty()) {
                        $nbProposale++;
                    }
                }
                $em = $this->getDoctrine()->getManager();

                $proposal = new Proposal();
                $form     = $this->createForm(ProposalFromType::class, $proposal)->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid() && $form->getData()->getFile() !== null) {

                    $proposal->setMission($userMission->getMission());
                    $em->persist($proposal);
                    $em->flush();

                    $this->get('inbox.services')->sendProposaleMessage($userMission, $user);

                    return $this->redirectToRoute('mission_answer_to_advisor', ['userMissionId' => $userMissionId]);
                }
                return $this->render('@Mission/Mission/Contractor/mission_answer_to_advisor.html.twig', [
                        'userMission' => $userMission,
                        'anonymous'   => $step->getAnonymousMode(),
                        'userId'      => $user->getId(),
                        'mission'     => $mission,
                        'nbProposale' => $nbProposale,
                        'interested'  => count($this->getDoctrine()
                            ->getRepository('MissionBundle:UserMission')
                            ->findAllAtLeastThan($mission, UserMission::ONGOING)),
                        'shortlisted' => count($this->getDoctrine()
                            ->getRepository('MissionBundle:UserMission')
                            ->findAllAtLeastThan($mission, UserMission::SHORTLIST)),
                        'user'        => $user,
                        'form'        => $form->createView()
                    ]);
            default:
                throw new NotFoundHttpException('No such mission w/ step in position' . $step->getPosition());
        }
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
            throw $this->createNotFoundException($trans->trans('error.logged', [], 'tools'));
        } elseif (!$this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            throw $this->createNotFoundException($trans->trans('error.forbidden', [], 'tools'));
        }

        // Get Check Mission
        $missionRepo = $em->getRepository('MissionBundle:Mission');
        if (!($mission = $missionRepo->findOneBy(['id' => $missionId]))
            || $mission->getStatus() !== Mission::PUBLISHED
            || $user->getCompany() !== $mission->getCompany()) {
            throw $this->createNotFoundException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        // Get Check Count UserMission
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
        if (!($step = $em->getRepository('MissionBundle:Step')->findOneby(['mission' => $mission, 'status'  => 1]))
            || !($nextStep = $em->getRepository('MissionBundle:Step')->findOneby(['mission'  => $mission, 'position' => $step->getPosition() + 1]))) {
            throw new NotFoundHttpException($trans->trans('error.mission.step_error', [], 'tools'));
        } elseif (($nbUser = count($userMissionRepo->findAllAtLeastThan($mission, UserMission::SHORTLIST))) < 1) {
            throw new NotFoundHttpException($trans->trans('error.mission.shortlist.not_enough', [], 'tools'));
        } elseif ($nbUser > $nextStep->getNbMaxUser()) {
            throw new NotFoundHttpException($trans->trans('error.mission.shortlist.to_much', [], 'tools'));
        }

        /** @var UserMission $oneUserMission */
        foreach ($userMissionRepo->findAllAtLeastThan($mission, UserMission::SCORED) as $oneUserMission) {
            if ($oneUserMission->getStatus() >= UserMission::SCORED
                && $oneUserMission->getStatus() < UserMission::SHORTLIST) {
                $oneUserMission->setStatus(UserMission::DISMISS);
            }
        }

        $step->setStatus(0);
        $nextStep->setStatus(1);
        $mission->setNbOngoing(count($userMissionRepo->findAllAtLeastThan($mission, UserMission::SHORTLIST)));
        $em->flush();

        return $this->redirectToRoute('mission_view', ['missionId' => $missionId]);
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
        $userMission  =
            $em->getRepository('MissionBundle:UserMission')->findOneBy(['user' => $user, 'mission' => $mission]);

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
            && $mission && $mission->getStatus() >= Mission::PUBLISHED && $userMission) {
            switch ($userMission->getStatus()) {
                case UserMission::GIVEUP:
                    throw $this->createNotFoundException($trans->trans('error.user_mission.already_giveup', [], 'tools'));
                case UserMission::DELETED:
                case UserMission::ENDDATE:
                case UserMission::DISMISS:
                    throw $this->createNotFoundException($trans->trans('error.user_mission.cant_giveup', [], 'tools'));
                case UserMission::SCORED:
                case UserMission::MATCHED:
                case UserMission::INTERESTED:
                    $mission->setNbOngoing($mission->getNbOngoing() - 1);
                    $userMission->setStatus(UserMission::GIVEUP);
                    $em->flush();

                    return $this->redirectToRoute('dashboard');
                case UserMission::ONGOING:
                    $mission->setNbOngoing($mission->getNbOngoing() - 1);
                    $userMission->setStatus(UserMission::GIVEUP);
                    $em->flush();
                    $param = [
                        'mission' => $mission->getId(),
                        'user'    => $user->getId(),
                    ];
                    // Add notification for advisor
                    $notification->new($user, 1, 'notification.expert.mission.giveup', $param);
                    // Add notification for contractors
                    $param      = [
                        'mission' => $mission->getId(),
                        'user'    => $user->getId(),
                    ];
                    $contractor = $mission->getContact();
                    $notification->new($contractor, 1, 'notification.seeker.mission.empty', $param);

                    return $this->redirectToRoute('dashboard', []);
            }
        }
        throw $this->createNotFoundException($trans->trans('mission.error.forbiddenAccess', [], 'MissionBundle'));
    }

    /**
     * Refuse the user from the mission
     * Contractor Action
     *
     * @param integer $userMissionId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \HttpHeaderException
     */
    public function refuseUserMissionAction($userMissionId)
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

        // Get Check UserMission
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
        if (!($userMission = $userMissionRepo->findOneBy(['id' => $userMissionId])) ||
            $userMission->getMission()->getCompany() !== $user->getCompany()) {
            throw new NotFoundHttpException($trans->trans('error.user_mission.not_found', [], 'tools'));
        } elseif ($userMission->getMission()->getNbOngoing() == 1) {
            throw new NotFoundHttpException($trans->trans('error.user_mission.not_enough', [], 'tools'));
        }

        $this->get('inbox.services')->sendMessage($em->getRepository('UserBundle:User')->findOneBy([
                'firstName' => 'WantMoreWork'
            ]),
            $trans->trans('mission.message.removed', [], 'tools'), $userMission->getThread());
        $userMission->setStatus(UserMission::DISMISS);
        $em->flush();

        return $this->redirectToRoute('mission_view', [
            'missionId' => $userMission->getMission()->getId()
        ]);
    }

    /**
     * Pass a user from shortlisted to contracted
     * Pass the mission to next step
     *
     * @param integer $userMissionId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addToContractAction($userMissionId)
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

        // Get Check UserMission
        /** @var \MissionBundle\Repository\UserMissionRepository $userMissionRepo */
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
        if (!($userMission = $userMissionRepo->findOneBy(['id' => $userMissionId]))
            || $userMission->getStatus() !== UserMission::SHORTLIST) {
            throw new NotFoundHttpException($trans->trans('error.user_mission.not_found', [], 'tools'));
        }

        // Get Check Mission And Step
        /** @var \MissionBundle\Entity\Mission $mission */
        if (($mission = $userMission->getMission()) === null
            || $mission->getStatus() !== Mission::PUBLISHED
            || $user->getCompany() !== $mission->getCompany()) {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        // Count UserMission By the Step
        if (($step = $em->getRepository('MissionBundle:Step')->findOneby(['mission' => $mission, 'status' => 1]))
            && ($nStep = $em->getRepository('MissionBundle:Step')->findOneby([
                'mission'  => $mission,
                'position' => $step->getPosition() + 1
            ]))
            && count($userMissionRepo->findAllAtLeastThan($mission, UserMission::FINALIST)) < $nStep->getNbMaxUser()) {

            /** @var UserMission $oneUserMission */
            foreach ($userMissionRepo->findAllAtLeastThan($mission, UserMission::SHORTLIST) as $oneUserMission) {
                if ($oneUserMission->getStatus() === UserMission::SHORTLIST) {
                    $oneUserMission->setStatus(UserMission::DISMISS);
                }
            }

            $this->get('inbox.services')->sendMessage($em->getRepository('UserBundle:User')->findOneBy([
                    'firstName' => 'WantMoreWork'
                ]),
                $trans->trans('mission.message.my_selection_added', ['%title' => $trans->trans(
                    $mission->getTitle(), [], 'tools'
                )], 'tools'), $userMission->getThread());
            $userMission->setStatus(UserMission::FINALIST);
            $mission->setNbOngoing(1);
            $nStep->setStatus(1);
            $step->setStatus(0);
            $em->flush();

            return $this->redirectToRoute('mission_view', ['missionId' => $mission->getId()]);
        }
        throw new NotFoundHttpException($trans->trans('error.mission.already', [], 'tools'));
    }

    /**
     * Mark a UserMission as SHORTLISTED
     *
     * @param $userMissionId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addToShortlistAction($userMissionId)
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

        // Get Check UserMission
        /** @var \MissionBundle\Repository\UserMissionRepository $userMissionRepo */
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
        if (!($userMission = $userMissionRepo->findOneBy(['id' => $userMissionId]))
            || $userMission->getStatus() !== UserMission::ONGOING) {
            throw new NotFoundHttpException($trans->trans('error.user_mission.not_found', [], 'tools'));
        }

        // Get Check Mission And Step
        /** @var \MissionBundle\Entity\Mission $mission */
        if (($mission = $userMission->getMission()) === null
            || $mission->getStatus() !== Mission::PUBLISHED
            || $user->getCompany() !== $mission->getCompany()) {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        // Count UserMission By the Step
        if (($step = $em->getRepository('MissionBundle:Step')->findOneby([
                'mission' => $mission, 'status'  => 1]))
            && ($nStep = $em->getRepository('MissionBundle:Step')->findOneby([
                'mission'  => $mission, 'position' => $step->getPosition() + 1]))
            && count($userMissionRepo->findAllAtLeastThan($mission, UserMission::SHORTLIST)) < $nStep->getNbMaxUser()) {

            $this->get('inbox.services')->sendMessage($em->getRepository('UserBundle:User')->findOneBy([
                'firstName' => 'WantMoreWork'
            ]),
                $trans->trans('mission.message.shortlist_added', ['%title' => $trans->trans(
                    $mission->getTitle(), [], 'tools'
                )], 'tools'),
                $userMission->getThread());
            $userMission->setStatus(UserMission::SHORTLIST);
            $em->flush();

            return $this->redirectToRoute('mission_view', ['missionId' => $mission->getId()]);
        }
        throw new NotFoundHttpException($trans->trans('error.user_mission.not_enough', [], 'tools'));
    }

    /**
     * @param $userMissionId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeFromShortlistAction($userMissionId)
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
        // Get Check UserMission
        /** @var \MissionBundle\Repository\UserMissionRepository $userMissionRepo */
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
        if (!($userMission = $userMissionRepo->findOneBy(['id' => $userMissionId]))
            || $userMission->getStatus() !== UserMission::SHORTLIST) {
            throw new NotFoundHttpException($trans->trans('error.user_mission.not_found', ['%id' => $userMissionId], 'tools'));
        }

        // Get Check Mission And Step
        /** @var \MissionBundle\Entity\Mission $mission */
        if (($mission = $userMission->getMission()) === null
            || $mission->getStatus() !== Mission::PUBLISHED
            || $user->getCompany() !== $mission->getCompany()) {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        // Count UserMission By the Step
        if (($step = $em->getRepository('MissionBundle:Step')->findOneby([
                'mission' => $mission, 'status'  => 1])) && $step->getPosition() !== 1) {
            throw new NotFoundHttpException($trans->trans('error.action.failed', [], 'tools'));
        }

        $this->get('inbox.services')->sendMessage($em->getRepository('UserBundle:User')->findOneBy([
            'firstName' => 'WantMoreWork'
        ]),
            $trans->trans('mission.message.removed', [], 'tools'), $userMission->getThread());
        $userMission->setStatus(UserMission::ONGOING);
        $em->flush();

        return $this->redirectToRoute('mission_view', ['missionId' => $mission->getId()]);
    }
}
