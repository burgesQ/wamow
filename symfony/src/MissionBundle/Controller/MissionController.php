<?php
namespace MissionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;

use Doctrine\Common\Collections\ArrayCollection;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use MissionBundle\Entity\Step;
use MissionBundle\Entity\Mission;
use MissionBundle\Form\MissionType;
use MissionBundle\Form\SelectionType;
use MissionBundle\Form\TakeBackType;
use TeamBundle\Entity\Team;
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
            $team = new Team(1, $this->getUser()); //role 1 = contractor
            $team->setStatus(1);
            $em->persist($team);
            $mission = new Mission($nbStep, $team, 1, $token, $company);
            $team->setMission($mission);
            $em->persist($team);
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
                    $step = new Step($array["nbMaxTeam"], $array["reallocTeam"]);
                    $step->setMission($mission);
                    $step->setPosition($i);
                    $em->persist($step);
                }
                $em->flush();

                // Add notification for contractor
                $notification = $this->container->get('notification');
                $users = $team->getUsers();
                $param = array(
                    'mission' => $mission->getId(),
                    'user'    => $user->getId(),
                );
                foreach ($users as $user)
                {
                    if ($user->getId() == $this->getUser()->getId())
                    {
                        $notification->new($this->getUser(), 1, 'notification.seeker.mission.create', $param);
                    }
                    else
                    {
                        //!\\ notification inactive
                        $notification->new($user, 0, 'notification.seeker.mission.createbyother', $param);
                    }
                }

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
        $service = $this->container->get('team');

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $em = $this->getDoctrine()->getManager();

            $mission = $em->getRepository('MissionBundle:Mission')->find($missionId);
            $user = $this->getUser();
            if (null === $mission) {
                throw new NotFoundHttpException($trans->trans('mission.error.wrongId', array('%id%' => $missionId), 'MissionBundle'));
            } elseif ($service->isContractorOfMission($user, $mission) == false) {
                throw new NotFoundHttpException($trans->trans('mission.error.right', array(), 'MissionBundle'));
            } elseif ($user->getCompany() != $mission->getCompany()) {
                throw new NotFoundHttpException($trans->trans('mission.error.wrongcompany', array(), 'MissionBundle'));
            } elseif ($mission->getStatus() != 0) {
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

                // Add notification for contractors
                $team = $mission->getTeamContact();
                $users = $team->getUsers();
                $notification = $this->container->get('notification');
                $param = array(
                    'mission' => $mission->getId(),
                    'user'    => $this->getUser()->getId(),
                );
                foreach ($users as $user)
                {
                    if ($user->getId() == $this->getUser()->getId())
                    {
                        $notification->new($this->getUser(), 1, 'notification.seeker.mission.edit', $param);
                    }
                    else
                    {
                        $notification->new($user, 1, 'notification.seeker.mission.editbyother', $param);
                    }
                }

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
    public function viewAction($missionId)
    {
        $em = $this->getDoctrine()->getManager();
        $trans = $this->get('translator');
        $service = $this->container->get('team');
        $mission = $em->getRepository('MissionBundle:Mission')->find($missionId);
        $user = $this->getUser();

        if ($user === null) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        } elseif ($mission == null ||  $mission->getStatus() < 0) {
            throw new NotFoundHttpException($trans->trans('mission.error.wrongId', array('%id%' => $missionId), 'MissionBundle'));
        }
        $listLanguage = $mission->getLanguages();
        if ( $this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $team = $mission->getTeamContact();
            if ($service->isContractorOfMission($user, $mission) == false) {
                throw new NotFoundHttpException($trans->trans('mission.error.right', array(), 'MissionBundle'));
            } elseif ($user->getCompany() != $mission->getCompany()) {
                throw new NotFoundHttpException($trans->trans('mission.error.wrongcompany', array(), 'MissionBundle'));
            }
            return $this->render('MissionBundle:Mission:view_seeker.html.twig', array(
                'mission'           => $mission,
                'listLanguage'      => $listLanguage));
        }
        elseif ( $this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR'))
        {
            if ($mission->getStatus() !== 1) {
                throw new NotFoundHttpException($trans->trans('mission.error.available', array('%id%' => $missionId), 'MissionBundle'));
            }
            return $this->render('MissionBundle:Mission:view_expert.html.twig', array(
                'mission'           => $mission,
                'listLanguage'      => $listLanguage,
                )
            );
        }
    }

    /*
    **  List
    */
    public function listAction()
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $repositoryMission = $em->getRepository('MissionBundle:Mission');
        $user = $this->getUser();

        if ( $user === null ) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            if ($user->getCompany() != null)
            {
                $listMission = $repositoryMission->getSeekerMissions($user->getId(), $user->getCompany()->getId());
            }
            return $this->render('MissionBundle:Mission:all_missions_seeker.html.twig', array(
                'listMission'           => $listMission
            ));
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')) {
            $listMission = $repositoryMission->getExpertMissionsAvailables();
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
    **  Pitch
    */
    public function pitchAction($missionId)
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        if ( $this->getUser() === null ) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        } elseif ( $this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR') ) {
            throw new NotFoundHttpException($trans->trans('mission.pitch.contractor', array(), 'MissionBundle'));
        }
        $repositoryMission = $em->getRepository('MissionBundle:Mission');
        $repositoryTeam = $em->getRepository('TeamBundle:Team');
        $listTeams = $repositoryTeam->getTeamsByUserId($this->getUser()->getId());
        $mission = $repositoryMission->findOneBy(array('id' => $missionId));
        if ($mission == null ||  $mission->getStatus() < 1) {
            throw new NotFoundHttpException($trans->trans('mission.error.available', array('%id%' => $missionId), 'MissionBundle'));
        }

        // Check if the expert has already pitch
        foreach ($listTeams as $team) {
            if ($team->getMission() == $mission) {
                return new Response($trans->trans('mission.pitch.twice', array(), 'MissionBundle'));
            }
        }

        // Create team
        $team = new Team(0, $this->getUser());  //role 0 = advisor
        $team->setMission($mission);
        $em->persist($team);
        $em->flush($team);

        // Add notification for advisors
        $param = array(
            'mission' => $mission->getId(),
            'user'    => $this->getUser()->getId(),
            'team'    => $team->getId(),
        );
        $notification = $this->container->get('notification');
        $advisors = $team->getUsers();
        foreach ($advisors as $advisor)
        {
            $notification->new($advisor, 1, 'notification.expert.mission.pitch', $param);
        }

        // Add notification for contractors
        $contractors = $mission->getTeamContact()->getUsers();
        foreach ($contractors as $contractor)
        {
            $notification->new($contractor, 1, 'notification.seeker.mission.pitchedbynewteam', $param);
        }


        return new Response($trans->trans('mission.pitch.done', array(), 'MissionBundle'));
    }

    /*
    ** Team Status :
    **     -3 : no user left in the team
    **     -2 : pitch deleted
    **     -1 : deleted by the contractor
    **      0 : waiting teams
    **      1 : first step
    **      2 : second step
    **      3 : winning teaam
    */
    public function selectionAction(Request $request, $missionId)
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $serviceTeam = $this->container->get('team');
        $serviceMission = $this->container->get('mission');
        $repositoryTeam = $em->getRepository('TeamBundle:Team');
        $repositoryStep = $em->getRepository('MissionBundle:Step');
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
        if ($mission == null || $mission->getStatus() != 1) {
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

        // Get teams
        $teams = $repositoryTeam->getAvailablesTeams($missionId, $step);

        // If there is no team in the mission
        if ($teams == null)
        {
            return new Response($trans->trans('mission.selection.noTeam', array(), 'MissionBundle'));
        }

        // If it's the first step keep the fastest teams of advisors
        if ($position == 1)
        {
            foreach ($teams as $team)
            {
                if ($team->getStatus() == 0)
                {
                    $team->setStatus($position);
                    $em->persist($team);
                    $param = array(
                        'mission' => $missionId,
                        'user'    => $user->getId(),
                        'step'    => $position,
                    );

                    // Add notifications for advisors
                    $advisors = $team->getUsers();
                    foreach ($advisors as $advisor)
                    {
                        $notification->new($advisor, 1, 'notification.expert.mission.beginselection', $param);
                    }
                }
            }
            $em->flush();
        }

        // If the selection is done
        if ($nextStep == null)
        {
            foreach ($teams as $team)
            {
                return new Response($trans->trans('mission.selection.finish', array('%id%' => $team->getId()), 'MissionBundle'));
            }
        }

        // Number of possibles teams
        $nbTeams = count($teams);

        $form = $this->get('form.factory')->create(new SelectionType($missionId, $position));
        $form->handleRequest($request);
        if ($this->get('request')->getMethod() == 'POST')
        {
            $data = $form->getData();
            $teamsAdvisors = $data['team'];
            $nbTeamsChosen = count($teamsAdvisors);

            // Selection de teams
            if ($form->get('save')->isClicked())
            {
                if ($nbTeamsChosen > $nextStep->getNbMaxTeam())
                {
                    $request->getSession()->getFlashBag()->add('notice', $trans->trans('mission.error.select.toomanyteams', array(), 'MissionBundle'));
                    return $this->redirectToRoute('mission_teams_selection', array(
                        'missionId' => $missionId
                    ));
                }
                else if ($nbTeamsChosen == 0 || $nbTeamsChosen != $nextStep->getNbMaxTeam())
                {
                    $request->getSession()->getFlashBag()->add('notice', $trans->trans('mission.error.select.notenoughteams', array(), 'MissionBundle'));
                    return $this->redirectToRoute('mission_teams_selection', array(
                        'missionId' => $missionId
                    ));
                }

                foreach ($teamsAdvisors as $teamAdvisors)
                {
                    $teamAdvisors->setStatus($nextStep->getPosition());
                    $em->persist($teamAdvisors);

                    $param = array(
                        'mission' => $missionId,
                        'user'    => $user->getId(),
                        'step'    => $nextStep->getPosition(),
                    );

                    // Add notifications for advisors
                    $advisors = $teamAdvisors->getUsers();
                    foreach ($advisors as $advisor)
                    {
                        $notification->new($advisor, 1, 'notification.expert.mission.selection', $param);
                    }

                    // Add notifications for contractors
                    $contractors = $mission->getTeamContact()->getUsers();
                    foreach ($contractors as $contractor)
                    {
                        $param = array(
                            'mission'      => $missionId,
                            'user'         => $user->getId(),
                            'step'         => $step->getPosition(),
                            'teamAdvisors' => $teamAdvisors->getId(),
                        );
                        if ($contractor->getId() == $user->getId())
                        {
                            $notification->new($user, 1, 'notification.seeker.mission.selection', $param);
                        }
                        else
                        {
                            $notification->new($contractor, 1, 'notification.seeker.mission.selectionbyother', $param);
                        }
                    }
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
                $teamsNotSelected = $repositoryTeam->findBy(array('mission' => $mission, 'status' => $position));
                foreach ($teamsNotSelected as $teamNotSelected)
                {
                    $advisors = $teamNotSelected->getUsers();
                    foreach ($advisors as $advisor)
                    {
                        $notification->new($advisor, 1, 'notification.expert.mission.notselected', $param);
                    }
                }

                return $this->redirectToRoute('mission_teams_selection', array(
                    'missionId' => $missionId
                ));
            }
            // Suppression de team
            else
            {
                if ($nbTeamsChosen == 0 || $nbTeamsChosen > $step->getReallocTeam())
                {
                    $request->getSession()->getFlashBag()->add('notice', $trans->trans('mission.error.select.toomanyteams', array(), 'MissionBundle'));
                    return $this->redirectToRoute('mission_teams_selection', array(
                        'missionId' => $missionId
                    ));
                }

                foreach ($teamsAdvisors as $teamAdvisors)
                {
                    $teamAdvisors->setStatus(-1);
                    $em->persist($teamAdvisors);

                    $param = array(
                        'mission' => $missionId,
                        'user'    => $user->getId(),
                        'step'    => $position,
                    );

                    // Add notifications for advisors
                    $advisors = $teamAdvisors->getUsers();
                    foreach ($advisors as $advisor)
                    {
                        $notification->new($advisor, 1, 'notification.expert.mission.dismiss', $param);
                    }

                    // Add notifications for contractors
                    $teamContractors = $mission->getTeamContact();
                    $contractors = $teamContractors->getUsers();
                    foreach ($contractors as $contractor)
                    {
                        $param = array(
                            'mission'      => $missionId,
                            'user'         => $user->getId(),
                            'step'         => $nextStep->getPosition(),
                            'teamAdvisors' => $teamAdvisors->getId(),
                        );
                        if ($contractor->getId() == $user->getId())
                        {
                            $notification->new($user, 1, 'notification.seeker.mission.dismiss', $param);
                        }
                        else
                        {
                            $notification->new($contractor, 1, 'notification.seeker.mission.dismissbyother', $param);
                        }
                    }
                }
                $em->flush();

                // if this is the first step the takeback is automatic
                if ($position == 1)
                {
                    foreach ($teams as $team)
                    {
                        if ($team->getStatus() == 0)
                        {
                            $team->setStatus($position);
                            $em->persist($team);
                            $param = array(
                                'mission' => $missionId,
                                'user'    => $user->getId(),
                                'step'    => $position,
                            );

                            // Add notifications for advisors
                            $advisors = $team->getUsers();
                            foreach ($advisors as $advisor)
                            {
                                $notification->new($advisor, 1, 'notification.expert.mission.beginselection', $param);
                            }
                        }
                    }
                    $em->flush();
                }
            }
            return $this->redirectToRoute('mission_teams_selection', array(
                'missionId' => $missionId
            ));
        }

        return $this->render('MissionBundle:Mission:selection.html.twig', array(
            'form' => $form->createView(),
            'missionId' => $missionId,
            'step' => $step,
            'nbTeams' => $nbTeams
        ));
    }

    /*
    **  Take back Advisor
    */
    public function takebackAction(Request $request, $missionId)
    {
        $em = $this->getDoctrine()->getManager();
        $trans = $this->get('translator');
        $mission = $em->getRepository('MissionBundle:Mission')->find($missionId);
        $step = $em->getRepository('MissionBundle:Step')->findOneby(array('mission' => $mission, 'status' => 1));
        if ($step == null)
        {
            throw new NotFoundHttpException($trans->trans('mission.error.stepnotfound', array(), 'MissionBundle'));
        }
        $position = $step->getPosition();


        $user = $this->getUser();
        if ($user === null) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR') || $mission === null || $mission->getStatus() < 0) {
            throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', array(), 'MissionBundle'));
        } elseif ($user->getCompany() != $mission->getCompany()) {
            throw new NotFoundHttpException($trans->trans('mission.error.wrongcompany', array(), 'MissionBundle'));
        }
        $form = $this->get('form.factory')->create(new TakeBackType($missionId, $position - 1));
        $form->handleRequest($request);
        if ($step->getStatus() != 1) {
            return $this->redirectToRoute('mission_teams_selection', array(
                'missionId' => $missionId
                ));
        }
        if ($step->getReallocTeam() == 0)
        {
            $request->getSession()->getFlashBag()->add('notice', $trans->trans('mission.selection.takeback', array(), 'MissionBundle'));
            return $this->redirectToRoute('mission_teams_selection', array(
                'missionId' => $missionId
            ));
        }
        if ($this->get('request')->getMethod() == 'POST')
        {
            $data = $form->getData();
            $teamsAdvisors = $data['team'];
            $notification = $this->container->get('notification');
            foreach ($teamsAdvisors as $teamAdvisors)
            {
                $step->setReallocTeam($step->getReallocTeam() - 1);
                $teamAdvisors->setStatus($position);
                $em->flush($teamAdvisors);

                // Add notifications for advisors
                $param = array(
                    'mission' => $mission->getId(),
                    'user'    => $user->getId(),
                    'step'    => $position,
                );
                $advisors = $teamAdvisors->getUsers();
                foreach ($advisors as $advisor)
                {
                    $notification->new($advisor, 1, 'notification.expert.mission.takeback', $param);
                }

                // Add notifications for contractors
                $teamContractors = $mission->getTeamContact();
                $contractors = $teamContractors->getUsers();
                foreach ($contractors as $contractor)
                {
                        $param = array(
                            'mission'      => $missionId,
                            'user'         => $user->getId(),
                            'step'         => $position,
                            'teamAdvisors' => $teamAdvisors->getId(),
                        );
                        if ($contractor->getId() == $this->getUser()->getId())
                        {
                            $notification->new($this->getUser(), 1, 'notification.seeker.mission.takeback', $param);
                        }
                        else
                        {
                            $notification->new($contractor, 1, 'notification.seeker.mission.takebackbyother', $param);
                        }
                }
            }
            $em->flush($step);
            return $this->redirectToRoute('mission_teams_selection', array(
                'missionId' => $missionId
            ));
        }
        return $this->render('MissionBundle:Mission:takeback.html.twig', array(
            'form' => $form->createView(),
            'missionId' => $missionId
            ));
    }

    /*
    **  Delete mission
    */
    public function deleteAction($missionId)
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $service = $this->container->get('team');
        $repository = $em->getRepository('MissionBundle:Mission');
        $mission = $repository->find($missionId);
        $user = $this->getUser();

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')
            && $mission != null && $service->isContractorOfMission($user, $mission) == true
            && $mission->getStatus() >= 0
            && $user->getCompany() == $mission->getCompany())
        {
            $mission->setStatus(-1);
            $teams = $em->getRepository('TeamBundle:Team')->getAdvisorsTeams($missionId);
            $notification = $this->container->get('notification');

            foreach ($teams as $team) {
                $team->setStatus(-2);

                // Add notifications for advisors
                $users = $team->getusers();
                foreach ($users as $user)
                {
                    $param = array(
                        'mission' => $mission->getId(),
                        'user'    => $user->getId(),
                    );
                    $notification->new($user, 1, 'notification.expert.mission.delete', $param);
                }
                $em->flush();
            }

            // Add notifications for contractors
            $team = $mission->getTeamContact();
            $users = $team->getUsers();
            foreach ($users as $user)
            {
                    $param = array(
                        'mission' => $mission->getId(),
                        'user'    => $this->getUser()->getId(),
                    );
                    if ($user->getId() == $this->getUser()->getId())
                    {
                        $notification->new($this->getUser(), 1, 'notification.seeker.mission.delete', $param);
                    }
                    else
                    {
                        $notification->new($user, 1, 'notification.seeker.mission.deletebyother', $param);
                    }
            }
            return $this->redirectToRoute('missions_all', array());
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
        $repository = $em->getRepository('TeamBundle:Team');
        $team = $repository->getTeamByMissionAndUser($missionId, $user->getId());

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
            && $mission != null && $team != null && $mission->getStatus() >= 1)
        {
            $param = array(
                'mission' => $mission->getId(),
                'user'    => $user->getId(),
            );

            $advisors = $team->getUsers();
            foreach ($advisors as $advisor)
            {
                // Add notification for advisors
                if ($advisor->getId() == $this->getUser()->getId())
                {
                    $notification->new($advisor, 1, 'notification.expert.mission.giveup', $param);
                }
                else
                {
                    $notification->new($advisor, 1, 'notification.expert.mission.giveupbyother', $param);
                }
            }

            $team->removeUser($user);
            $user->setGiveUpCount($user->getGiveUpCount() + 1);
            $em->persist($team);
            $em->flush();

            // If there is no advisor in the team
            if (count($advisors) == 0)
            {
                $team->setStatus(-3);

                // Add notification for contractors
                $param = array(
                    'mission' => $mission->getId(),
                    'team'    => $team->getId(),
                );
                $contractors = $mission->getTeamContact()->getUsers();

                //TODO : voir si on active cette option --> possibilite de repecher une team si une team se desinscrit pdt le process
                //$repositoryStep = $em->getRepository('MissionBundle:Step');
                //$step = $repositoryStep->findOneby(array('mission' => $mission, 'status' => 1));
                //$step->setReallocTeam($step->getReallocTeam + 1);
                foreach ($contractors as $contractor)
                {
                        $notification->new($contractor, 1, 'notification.seeker.mission.emptyteam', $param);
                        //TODO : a ajouter dans translator
                        //$notification->new($contractor, 1, 'notification.seeker.mission.addcounter', $param);
                }
            }
            return $this->redirectToRoute('dashboard', array());
        }
        throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', array(), 'MissionBundle'));
    }
}
