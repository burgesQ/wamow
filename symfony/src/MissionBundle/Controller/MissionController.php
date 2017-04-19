<?php
namespace MissionBundle\Controller;

use InboxBundle\Repository\MessageRepository;
use InboxBundle\Repository\ThreadRepository;
use InboxBundle\Services\Services;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use MissionBundle\Entity\Step;
use MissionBundle\Entity\Mission;
use MissionBundle\Entity\UserMission;
use MissionBundle\Form\MissionType;
use MissionBundle\Form\SelectionType;
use MissionBundle\Form\TakeBackType;
use ToolsBundle\Entity\Tag;

class MissionController extends Controller
{
    /*
    **  Create a new mission
    */
    public function newAction(Request $request)
    {
        $trans = $this->get('translator');
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $user = $this->getUser();
            $company = $user->getCompany();
            if ($user->getCompany() == null)
            {
                $request->getSession()->getFlashBag()->add('notice', $trans->trans('mission.error.needCompany', array(), 'MissionBundle'));
                return $this->redirectToRoute('company_join');
            }

            $em = $this->getDoctrine()->getManager();
            $repository = $this
                        ->getDoctrine()
                        ->getManager()
                        ->getRepository('MissionBundle:Mission')
                        ;
            $service = $this->container->get('config.mission');
            $nbStep = $service->getMissionNbStep();
            $user = $this->getUser();
            $token = bin2hex(random_bytes(10));
            while ($repository->findByToken($token) != null) {
                $token = bin2hex(random_bytes(10));
            }
            $mission = new Mission($nbStep, $user, $token, $company);
            $form = $this->get('form.factory')->create(new MissionType(), $mission);
            $form->handleRequest($request);
            if ($form->isValid())
            {
              $em = $this->getDoctrine()->getManager();
              if ($_POST)
                {
                    if (isset($_POST['mission']['tags']))
                    {
                        $values = $_POST['mission']['tags'];
                        foreach ($values as $value)
                        {
                            $tag = new Tag();
                            $tag->setTag($value);
                            $mission->addTag($tag);
                            $em->persist($tag);
                        }
                    $em->flush();
                    }
                }
                $em->persist($mission);
                for ($i=1; $i <= $nbStep; $i++)
                {
                    $array = $service->getStepConfig($i);
                    $step = new Step($array["nbMaxUser"], $array["reallocUser"]);
                    $step->setMission($mission);
                    $step->setPosition($i);
                    $em->persist($step);
                }
                $em->flush();

                // Add notification for contractor
                $notification = $this->container->get('notification');
                $param = array(
                    'mission' => $mission->getId(),
                    'user'    => $user->getId(),
                );
                $notification->new($this->getUser(), 1, 'notification.seeker.mission.create', $param);

                $this->get('mission_matching')->setUpPotentialUser($mission);

                return $this->render('MissionBundle:Mission:registered.html.twig', array(
                    'mission'       =>   $mission,
                ));
            }
            return $this->render('MissionBundle:Mission:new.html.twig', array(
                'form' => $form->createView(),
            ));
        }
        else {
            return new Response($trans->trans('mission.error.authorized', array(), 'MissionBundle'));
        }
    }

    /*
    **  Edit a mission
    */
    public function editAction($missionId, Request $request)
    {
        $trans = $this->get('translator');

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $em = $this->getDoctrine()->getManager();

            $mission = $em->getRepository('MissionBundle:Mission')->find($missionId);
            $user = $this->getUser();
            if (null === $mission) {
                throw new NotFoundHttpException($trans->trans('mission.error.wrongId', array('%id%' => $missionId), 'MissionBundle'));
            } elseif ($mission->getContact() != $user) {
                throw new NotFoundHttpException($trans->trans('mission.error.right', array(), 'MissionBundle'));
            } elseif ($user->getCompany() != $mission->getCompany()) {
                throw new NotFoundHttpException($trans->trans('mission.error.wrongcompany', array(), 'MissionBundle'));
            } elseif ($mission->getStatus() != Mission::DRAFT) {
                throw new NotFoundHttpException($trans->trans('mission.error.alreadyEdit', array(), 'MissionBundle'));
            }

            $form = $this->get('form.factory')->create(new MissionType(), $mission);
            $form->handleRequest($request);

            if ($form->isValid()) {
          		if ($_POST)
            	{
           			if (isset($_POST['mission']['tags']))
                    {
                        $values = $_POST['mission']['tags'];
               	        foreach ($values as $value)
                	    {
                  		    $tag = new Tag();
                  		    $tag->setTag($value);
                  		    $mission->addTag($tag);
                  		    $em->persist($tag);
             		    }
                    }
           		}
                $step = $em->getRepository('MissionBundle:Step')->findOneBy(array('mission' => $mission, 'position' => 1));
                $mission->setStatus(1);
                $step->setStatus(1);
                $em->flush();

                $this->get('mission_matching')->setUpPotentialUser($mission, true);

                return new Response($trans->trans('mission.edit.successEdit', array(), 'MissionBundle'));
            }
            return $this->render('MissionBundle:Mission:edit.html.twig', array(
                'form' => $form->createView(),
                'mission' => $mission,
            ));
        }
        throw new NotFoundHttpException($trans->trans('mission.error.contractor', array(), 'MissionBundle'));
    }

    /*
    **  View mission
    */
    public function viewAction($missionId, Request $request)
    {
        $em      = $this->getDoctrine()->getManager();
        $trans   = $this->get('translator');
        $mission = $em->getRepository('MissionBundle:Mission')->find($missionId);
        $user    = $this->getUser();

        if ($user === null) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', [], 'MissionBundle'));
        } elseif ($mission == null || $mission->getStatus() < Mission::DRAFT) {
            throw new NotFoundHttpException($trans->trans('mission.error.wrongId', ['%id%' => $missionId], 'MissionBundle'));
        }

        $serviceInbox    = $this->container->get('inbox.services');
        /** @var ThreadRepository $threadRepo */
        $threadRepo      = $em->getRepository('InboxBundle:Thread');
        $messageRepo     = $em->getRepository('InboxBundle:Message');
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
        $repositoryStep  = $em->getRepository('MissionBundle:Step');

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            if ($user->getCompany() != $mission->getCompany()) {
                throw new NotFoundHttpException($trans->trans('mission.error.wrongcompany', [], 'MissionBundle'));
            }

            $bigArray = $serviceInbox->setViewContractor($user, $mission, $request);

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

            if (($url = $this->get('signedUp')->checkIfSignedUp($this))) {
                return $this->redirectToRoute($url);
            } elseif ($mission->getStatus() !== Mission::PUBLISHED) {
                throw new NotFoundHttpException($trans
                    ->trans('mission.error.available', ['%id%' => $missionId], 'MissionBundle'));
            }

            $userMission = $userMissionRepo->findOneBy(['user' => $user, 'mission' => $mission]);
            $thread      = $threadRepo->findOneBy(['userMission' => $userMission]);

            /** @var MessageRepository $messageRepository */
            $replyForm         = null;

            if ($thread) {
                $replyForm = $serviceInbox->getReplyForm($thread, $user, $request);

                // if needed reload the page
                if ($replyForm == null) {
                    return $this->redirectToRoute('mission_view', ['missionId' => $missionId]);
                }
            }
            $serviceInbox->updateReadReport($thread, $user);
            return $this->render('MissionBundle:Mission:view_expert.html.twig', [
                'user'         => $user,
                'mission'      => $mission,
                'thread'       => $thread,
                'arrayMessage' => $messageRepo->getMessageForThread($thread, $user->getNbLoad()),
                'replyForm'    => $replyForm,
                'step'         => $repositoryStep->findOneBy(['mission' => $mission, 'status' => 1]),
            ]);
        }
    }

    /*
    **  List
    */
    public function listAction()
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($user === null) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $listMission = $em->getRepository('MissionBundle:Mission')->findBy(array('contact' => $user, 'company' => $user->getCompany()));
            return $this->render('MissionBundle:Mission:all_missions_seeker.html.twig', array(
                'listMission'           => $listMission
            ));
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR'))
        {
            if (($url = $this->get('signedUp')->checkIfSignedUp($this)))
            {
                return $this->redirectToRoute($url);
            }
            $listMission = array();
            $userMissions = $em->getRepository('MissionBundle:UserMission')->findBy(array('user' => $user));
            foreach ($userMissions as $userMission) {
                array_push($listMission, $userMission->getMission());
            }
            return $this->render('MissionBundle:Mission:all_missions_expert.html.twig', array(
                'listMission'           => $listMission
            ));
        }
        else
        {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        }
    }

    /*
    **  Interested
    */
    public function interestedAction($missionId)
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        // Security check
        if (($user = $this->getUser()) === null )
        {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR') )
        {
            throw new NotFoundHttpException($trans->trans('mission.pitch.contractor', array(), 'MissionBundle'));
        }
        elseif (($url = $this->get('signedUp')->checkIfSignedUp($this)))
        {
            return $this->redirectToRoute($url);
        }

        // Check mission
        $missionRepo = $em->getRepository('MissionBundle:Mission');
        $mission = $missionRepo->findOneBy(array('id' => $missionId));
        if ($mission == null || $mission->getStatus() < Mission::PUBLISHED)
        {
            throw new NotFoundHttpException($trans->trans('mission.error.available', array('%id%' => $missionId), 'MissionBundle'));
        }

        // Check UserMission
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
        if (!($userMission = $userMissionRepo->findOneby(array('mission' => $mission, 'user' => $user))))
        {
            throw new NotFoundHttpException($trans->trans('mission.error.usermission', array(), 'MissionBundle'));
        }

        // Check Status UserMission
        if ($userMission->getStatus() == UserMission::NEW)
        {
            $userMission->setStatus(UserMission::INTERESTED);
            $em->flush();
            new Response($trans->trans('mission.interested.done', array(), 'MissionBundle'));
            return $this->redirectToRoute('dashboard', array());
        }
        throw new NotFoundHttpException($trans->trans('mission.pitch.forbidden', array(), 'MissionBundle'));
    }

    /*
    **  Pitch
    */
    public function pitchAction($missionId)
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        if (($user = $this->getUser()) === null )
        {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        }
        elseif ( $this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR') )
        {
            throw new NotFoundHttpException($trans->trans('mission.pitch.contractor', array(), 'MissionBundle'));
        }
        elseif (($url = $this->get('signedUp')->checkIfSignedUp($this)))
        {
            return $this->redirectToRoute($url);
        }

        $repositoryMission = $em->getRepository('MissionBundle:Mission');
        $mission = $repositoryMission->findOneBy(array('id' => $missionId));
        if ($mission == null || $mission->getStatus() < Mission::PUBLISHED) {
            throw new NotFoundHttpException($trans->trans('mission.error.available', array('%id%' => $missionId), 'MissionBundle'));
        }

        // Check if the advisor has already pitch
        /** @var UserMission $userMission */
        $userMission = $em->getRepository('MissionBundle:UserMission')
            ->findOneBy(['user' => $user, 'mission' => $mission]);

        if ($userMission->getStatus() >= UserMission::ONGOING)
        {
            return new Response($trans->trans('mission.pitch.twice', array(), 'MissionBundle'));
        }
        elseif ($userMission->getStatus() < UserMission::NEW)
        {
            return new Response($trans->trans('mission.pitch.forbidden', array(), 'MissionBundle'));
        }

        // Create a thread between the Advisor and the thread
        $this->get('inbox.services')->createThreadPitch($userMission);

        // Add expert
        $userMission->setStatus(UserMission::ONGOING);
        $em->flush();

        // Add notification for advisor
        $param = [
            'mission' => $mission->getId(),
            'user'    => $user,
        ];

        $notification = $this->container->get('notification');
        $notification->new($user, 1, 'notification.expert.mission.pitch', $param);

        // Add notification for contractor
        $contractor = $mission->getContact();
        $notification->new($contractor, 1, 'notification.seeker.mission.pitchedbynewuser', $param);

        return new Response($trans->trans('mission.pitch.done', array(), 'MissionBundle'));
    }

	/*
    **  Selection of Advisors
	*/
    public function selectionAction(Request $request, $missionId)
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $repositoryStep = $em->getRepository('MissionBundle:Step');
        $repositoryUserMission = $em->getRepository('MissionBundle:UserMission');
        $notification = $this->container->get('notification');

        // Security
        $user = $this->getUser();
        if ($user === null) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        } elseif (!$this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', array(), 'MissionBundle'));
        }

        // Get mission
        $mission = $em->getRepository('MissionBundle:Mission')->findOneById($missionId);

        // Check if mission exists and if is available
        if ($mission == null || $mission->getStatus() != Mission::PUBLISHED) {
            throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', array(), 'MissionBundle'));
        }  elseif ($user->getCompany() != $mission->getCompany()) {
            throw new NotFoundHttpException($trans->trans('mission.error.wrongcompany', array(), 'MissionBundle'));
        }

        // Get steps & position
        $step = $repositoryStep->findOneBy(array('mission' => $mission, 'status' => 1));

        if ($step == null)
        {
            throw new NotFoundHttpException($trans->trans('mission.error.stepnotfound', array(), 'MissionBundle'));
        }
        $position = $step->getPosition();
        $nextStep = $repositoryStep->findOneBy(array('mission' => $mission, 'position' => $position + 1));

        $usersMission = $repositoryUserMission->getAvailablesUsers($missionId, $step, false);

        // If the selection is done
        if ($nextStep == null)
        {
            foreach ($usersMission as $userMission)
            {
                return new Response($trans->trans('mission.selection.finish', array('%id%' => $userMission->getUser()->getId(), 'MissionBundle')));
            }
        }

        // Number of possible users
        $nbUsers = count($usersMission);

        $form = $this->get('form.factory')->create(new SelectionType($missionId, $step));
        $form->handleRequest($request);
        if ($this->get('request')->getMethod() == 'POST')
        {
            $data = $form->getData();

            $advisors = $data['userMission'];
            $nbAdvisorsChosen = count($advisors);

            // Select users
            if ($form->get('save')->isClicked())
            {
                if ($nbAdvisorsChosen > $nextStep->getNbMaxUser())
                {
                    $request->getSession()->getFlashBag()->add('notice', $trans->trans('mission.error.select.toomanyusers', array(), 'MissionBundle'));
                    return $this->redirectToRoute('mission_users_selection', array(
                        'missionId' => $missionId
                    ));
                }
                else if ($nbAdvisorsChosen < $nextStep->getNbMaxUser())
                {
                    $request->getSession()->getFlashBag()->add('notice', $trans->trans('mission.error.select.notenoughusers', array(), 'MissionBundle'));
                    return $this->redirectToRoute('mission_users_selection', array(
                        'missionId' => $missionId
                    ));
                }

                //$messenger = $this->get('inbox.services');

                foreach ($advisors as $advisor)
                {
                    $advisor->setStatus($nextStep->getPosition());
                    $em->persist($advisor);

                    $param = array(
                        'mission' => $missionId,
                        'user'    => $user->getId(),
                        'step'    => $nextStep->getPosition(),
                    );

                    /** @var Services    $inboxService */
                    $inboxService = $this->container->get('inbox.services');
                    /** @var UserMission $userMission */
                    $userMission  = $repositoryUserMission->findOneBy(['user' => $advisor, 'mission' => $mission]);
                    // TODO => if $userMission === null (user that wanna pitch wasn't selected by the algo)
                    // Create a thread if it's not already done if next step of the mission is ok with that
                    if ($nextStep->getAnonymousMode() > 0) {
                        $thread = $inboxService->createThreadPitch($userMission);
                    }

                    // Add notifications for advisors
                    $notification->new($advisor->getUser(), 1, 'notification.expert.mission.selection', $param);

                    // Add notifications for contractors
                    $param = array(
                        'mission'      => $missionId,
                        'user'         => $user->getId(),
                        'step'         => $step->getPosition(),
                        'user' => $advisor->getUser()->getId(),
                    );
                    $notification->new($user, 1, 'notification.seeker.mission.selection', $param);
                }
                $nextStep->setStatus(1);
                $nextStep->setStart(new \DateTime());
                $step->setStatus(0);
                $step->setEnd(new \DateTime());
                $em->persist($step);
                $em->persist($nextStep);

                $param = array(
                    'mission' => $missionId,
                    'user'    => $user->getId(),
                    'step'    => $position,
                );

                $em->flush();
                // Add notifications for advisors not selected
                $usersNotSelected = $repositoryUserMission->findBy(array('mission' => $mission, 'status' => $position));
                foreach ($usersNotSelected as $userNotSelected)
                {
                    $notification->new($userNotSelected->getUser(), 1, 'notification.expert.mission.notselected', $param);
                }

                return $this->redirectToRoute('mission_users_selection', array(
                    'missionId' => $missionId
                ));
            }
            // Delete users
            else
            {
                //TODO verifier si le nombre de suppression est inferieur au nombre de users dispo dans la step du dessous...
                if ($nbAdvisorsChosen == 0 || $nbAdvisorsChosen > $step->getReallocUser())
                {
                    $request->getSession()->getFlashBag()->add('notice', $trans->trans('mission.error.select.toomanyusers', array(), 'MissionBundle'));
                    return $this->redirectToRoute('mission_users_selection', array(
                        'missionId' => $missionId
                    ));
                }

                foreach ($advisors as $advisor)
                {
                    $advisor->setStatus(UserMission::DISMISS);
                    $em->persist($advisor);

                    $param = array(
                        'mission' => $missionId,
                        'user'    => $user->getId(),
                        'step'    => $position,
                    );

                    // Add notifications for advisors
                    $notification->new($advisor->getUser(), 1, 'notification.expert.mission.dismiss', $param);

                    // Add notifications for contractors
                    $contractor = $mission->getContact();
                    $param = array(
                            'mission'      => $missionId,
                            'user'         => $user->getId(),
                            'step'         => $nextStep->getPosition(),
                            'advisor'      => $advisor->getUser()->getId(),
                    );
                    $notification->new($user, 1, 'notification.seeker.mission.dismiss', $param);
                }
                $em->flush();
            }
            return $this->redirectToRoute('mission_users_selection', array(
                'missionId' => $missionId
            ));
        }

        return $this->render('MissionBundle:Mission:selection.html.twig', array(
            'form' => $form->createView(),
            'missionId' => $missionId,
            'step' => $step,
            'nbUsers' => $nbUsers
        ));
    }

    /*
    **  Take back Advisor
    */
    public function takebackAction(Request $request, $missionId)
    {
        $trans = $this->get('translator');

        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('MissionBundle:Mission')->find($missionId);
        $step = $em->getRepository('MissionBundle:Step')->findOneby(array('mission' => $mission, 'status' => 1));
        $oldStep = $em->getRepository('MissionBundle:Step')->findOneby(array('mission' => $mission, 'position' => ($step->getPosition() - 1)));

        if ($step == null)
        {
            throw new NotFoundHttpException($trans->trans('mission.error.stepnotfound', array(), 'MissionBundle'));
        }

        $position = $step->getPosition();
        $user = $this->getUser();

        if ($user === null) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR') || $mission === null || $mission->getStatus() < Mission::PUBLISHED) {
            throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', array(), 'MissionBundle'));
        } elseif ($user->getCompany() != $mission->getCompany()) {
            throw new NotFoundHttpException($trans->trans('mission.error.wrongcompany', array(), 'MissionBundle'));
        }

        if ($step->getReallocUser() == 0)
        {
            $request->getSession()->getFlashBag()->add('notice', $trans->trans('mission.selection.takeback', array(), 'MissionBundle'));
            return $this->redirectToRoute('mission_users_selection', array(
                'missionId' => $missionId
            ));
        }

        $repositoryUserMission = $em->getRepository('MissionBundle:UserMission');
        $vacancies = $step->getNbMaxUser() - count($repositoryUserMission->getAvailablesUsers($missionId, $step, false));

        $form = $this->get('form.factory')->create(new TakeBackType($missionId, $oldStep));
        $form->handleRequest($request);

        if ($this->get('request')->getMethod() == 'POST')
        {
            $data = $form->getData();
            $advisors = $data['userMission'];
            $notification = $this->container->get('notification');
            /** @var Services    $inboxService */
            $inboxService = $this->container->get('inbox.services');

            foreach ($advisors as $advisor)
            {
                $step->setReallocUser($step->getReallocUser() - 1);
                $advisor->setStatus($position);

                /** @var UserMission $userMission */
                $userMission  = $repositoryUserMission->findOneBy(['user' => $advisor, 'mission' => $mission]);
                // TODO => if $userMission === null (user that wanna pitch wasn't selected by the algo)
                if ($step->getAnoymousMode() > 0 ) {
                    $thread = $inboxService->createThreadPitch($userMission);
                }
                $em->flush($advisor);

                // Add notifications for advisor
                $param = array(
                    'mission' => $mission->getId(),
                    'user'    => $user->getId(),
                    'step'    => $position,
                );
                $notification->new($advisor->getUser(), 1, 'notification.expert.mission.takeback', $param);

                // Add notifications for contractor
                $contractor = $mission->getContact();
                $param = array(
                    'mission'      => $missionId,
                    'user'         => $user->getId(),
                    'step'         => $position,
                    'advisor'      => $advisor->getUser()->getId(),
                );
                $notification->new($this->getUser(), 1, 'notification.seeker.mission.takeback', $param);
            }
            $em->flush($step);
            return $this->redirectToRoute('mission_users_selection', array(
                'missionId' => $missionId
            ));
        }
        return $this->render('MissionBundle:Mission:takeback.html.twig', array(
            'form' => $form->createView(),
            'missionId' => $missionId,
            'vacancies' => $vacancies
            ));
    }

    /*
    **  Delete mission
    */
    public function deleteAction($missionId)
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('MissionBundle:Mission')->find($missionId);
        $user = $this->getUser();

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')
            && $mission != null
            && $mission->getContact() == $user
            && $mission->getStatus() == Mission::DRAFT
            && $user->getCompany() == $mission->getCompany())
        {

            $mission->setStatus(-1);
            $notification = $this->container->get('notification');
            $steps = $em->getRepository('MissionBundle:Step')->getStepsAvailables($missionId);
            foreach ($steps as $step)
            {
                $step->setStatus(-1);
                $em->persist($step);
            }

            $param = array(
                'mission' => $mission->getId(),
                'user'    => $user->getId(),
            );

            $usersMission = $em->getRepository('MissionBundle:UserMission')->findBy(array('mission' => $mission));
            foreach ($usersMission as $userMission) {
                $userMission->setStatus(UserMission::DELETED);
                $em->persist($userMission);

                // Add notifications for advisors
                $notification->new($userMission->getUser(), 1, 'notification.expert.mission.delete', $param);

            }
            // Add notifications for contractor
            $notification->new($user, 1, 'notification.seeker.mission.delete', $param);

            $em->flush();
            return $this->redirectToRoute('dashboard', array());
        }
        throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', array(), 'MissionBundle'));
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
