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
      Create a new mission
    */
    public function newAction(Request $request)
    {
        if ($this->container->get('security.authorization_checker')
            ->isGranted('ROLE_CONTRACTOR'))
        {
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
            $mission = new Mission($nbStep, $team, 1, $token);
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
                $missionId = $mission->getId();
                return $this->render('MissionBundle:Mission:registered.html.twig', array(
                    'mission'  =>   $mission,
                    'id'       =>   $missionId,
                    'form'     =>   $form,
                ));
            }
            return $this->render('MissionBundle:Mission:new.html.twig', array(
                'form' => $form->createView(),
            ));
        }
        else {
            return new Response($this->get('translator')->trans('mission.error.authorized', array(), 'MissionBundle'));
        }
    }

    /*
    ** If contractor -> edit if he created else error
    ** If advisor -> kick
    ** If not log -> kick
    */
    public function editAction($id, Request $request)
    {
        $trans = $this->get('translator');
        $service = $this->container->get('team');

        if ( $this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $em = $this->getDoctrine()->getManager();
            $mission = $em->getRepository('MissionBundle:Mission')->find($id);
            if (null === $mission) {
                throw new NotFoundHttpException($trans->trans('mission.error.wrongId', array('%id%' => $id), 'MissionBundle'));
            } elseif ($service->isContractorOfMission($this->getUser(), $mission) == false) {
                throw new NotFoundHttpException($trans->trans('mission.error.right', array(), 'MissionBundle'));
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
                $mission->setStatus(1);
                $em->flush();
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
    ** If contracotr -> show if created by him else error
    ** If advisor -> show if status === 1
    ** if not log -> kick
    */
    public function viewAction($missionId)
    {
        $em = $this->getDoctrine()->getManager();
        $trans = $this->get('translator');
        $service = $this->container->get('team');
        $mission = $em->getRepository('MissionBundle:Mission')->find($missionId);

        if ( $this->getUser() === null) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        } elseif ($mission == null ||  $mission->getStatus() < 0) {
            throw new NotFoundHttpException($trans->trans('mission.error.wrongId', array('%id%' => $missionId), 'MissionBundle'));
        }
        $listLanguage = $mission->getLanguages();
        if ( $this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $team = $mission->getTeamContact();
            if ($service->isContractorOfMission($this->getUser(), $mission) == false) {
                throw new NotFoundHttpException($trans->trans('mission.error.right', array(), 'MissionBundle'));
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
                'listLanguage'      => $listLanguage, ) );
        }
    }

    /*
    ** If contractor -> show mission by him
    ** If advisor -> show mission where status === 1
    ** If not log -> kick
    */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryMission = $em->getRepository('MissionBundle:Mission');
        $repositoryTeam = $em->getRepository('TeamBundle:Team');
        $user = $this->getUser();
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        if ( $user === null ) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $listTeams = $repositoryTeam->getTeamsByUserId($user->getId());
            return $this->render('MissionBundle:Mission:all_missions_seeker.html.twig', array(
                'listTeams'           => $listTeams
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
        foreach ($listTeams as $team) {
            if ($team->getMission() == $mission) {
                return new Response($trans->trans('mission.pitch.twice', array(), 'MissionBundle'));
            }
        }
        if ($mission->getStatus() !== 1) {
            throw new NotFoundHttpException($trans->trans('mission.error.available', array('%id%' => $missionId), 'MissionBundle'));
        }

        $team = new Team(0, $this->getUser());  //role 0 = advisor
        $team->setMission($mission);
        $em->persist($team);
        $em->flush($team);
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
        $serviceTeam = $this->container->get('team');
        $serviceMission = $this->container->get('mission');
        $em = $this->getDoctrine()->getManager();
        $repositoryTeam = $em->getRepository('TeamBundle:Team');
        $repositoryStep = $em->getRepository('MissionBundle:Step');
        $mission = $em->getRepository('MissionBundle:Mission')->find($missionId);
        if ($mission == null || $mission->getStatus() != 1) {
            throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', array(), 'MissionBundle'));
        }

        if ( $this->getUser() === null ) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        } elseif ( $this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR') ||  $mission->getStatus() < 0) {
            throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', array(), 'MissionBundle'));
        }

        $step = $repositoryStep->getStepByMissionAndPosition($missionId);
        $position = $step->getPosition();
        if ($repositoryStep->getStepByMissionAndPosition($missionId, $position + 1) == null) {
            $team = $repositoryTeam->findOneBy(array('mission' => $mission, 'status' => $position));
            return new Response($trans->trans('mission.selection.finish', array('%id%' => $team->getId()), 'MissionBundle'));
        }
        $teamToChoose = $repositoryTeam->getAvailablesTeams($missionId, $step);
        if ($teamToChoose == null)
            return new Response($trans->trans('mission.selection.noTeam', array(), 'MissionBundle'));
        $nbTeamToChoose = count($teamToChoose);
        $serviceTeam->setTeamsAvailable($teamToChoose, $position);

        $form = $this->get('form.factory')->create(new SelectionType($missionId, $position));
        $form->handleRequest($request);
        if ($this->get('request')->getMethod() == 'POST')
        {
            $data = $form->getData();
            $nbChosen = count($data['team']);
            if ($form->get('save')->isClicked())
            {
                if ($nbChosen > $repositoryStep->getStepByMissionAndPosition($missionId, $position + 1)->getNbMaxTeam() || $nbChosen == 0)
                {
                    $serviceMission->sendDeleteMessageInView($request, $nbChosen, $nbTeamToChoose, $repositoryStep->getStepByMissionAndPosition($missionId, $position + 1), $trans);
                    return $this->redirectToRoute('mission_teams_selection', array(
                        'missionId' => $missionId
                    ));
                }
                $serviceTeam->keepTeams($data['team'], $step);
                $nextStep = $repositoryStep->getStepByMissionAndPosition($missionId, $position + 1);
                $nextStep->setStatus(1);
                $nextStep->setStart(new \DateTime());
                $step->setEnd(new \DateTime());
                $em->flush($step);
                $em->flush($nextStep);
                return $this->redirectToRoute('mission_teams_selection', array(
                    'missionId' => $missionId
                ));
            }
            if ($nbChosen > $step->getReallocTeam() || $nbTeamToChoose - $nbChosen <= 0 || $step->getReallocTeam() == 0)
            {
                $serviceMission->sendDeleteMessageInView($request, $nbChosen, $nbTeamToChoose, $step, $trans);
                return $this->redirectToRoute('mission_teams_selection', array(
                    'missionId' => $missionId
                    ));
            }
            $em->flush($step);
            $serviceTeam->deleteTeams($data['team'], $step);
            $teamToChoose = $repositoryTeam->getAvailablesTeams($missionId, $step);
            $serviceTeam->setTeamsAvailable($teamToChoose, $position);
            return $this->redirectToRoute('mission_teams_selection', array(
                'missionId' => $missionId
                ));
        }

        return $this->render('MissionBundle:Mission:selection.html.twig', array(
            'form' => $form->createView(),
            'missionId' => $missionId,
            'step' => $step,
            'nbTeam' => $nbTeamToChoose
            ));
    }

    public function takebackAction(Request $request, $missionId)
    {
        $em = $this->getDoctrine()->getManager();
        $trans = $this->get('translator');
        $repositoryStep = $em->getRepository('MissionBundle:Step');
        $step = $repositoryStep->getStepByMissionAndPosition($missionId);
        $mission = $em->getRepository('MissionBundle:Mission')->find($missionId);

        if ( $this->getUser() === null ) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        } elseif ( $this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR') ||  $mission->getStatus() < 0) {
            throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', array(), 'MissionBundle'));
        }
        $position = $step->getPosition();
        $form = $this->get('form.factory')->create(new TakeBackType($missionId, $position - 1));
        $form->handleRequest($request);
        if ($step->getStatus() != 1) {
            return $this->redirectToRoute('mission_teams_selection', array(
                'missionId' => $missionId
                ));
        }
        if ($step->getReallocTeam() == 0)
        {
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $trans->trans('mission.selection.takeback', array(), 'MissionBundle'))
                ;
                return $this->redirectToRoute('mission_teams_selection', array(
                    'missionId' => $missionId
                ));
        }
        if ($this->get('request')->getMethod() == 'POST')
        {
            $data = $form->getData();
            $teams = $data['team'];
            foreach ($teams as $team) {
                $step->setReallocTeam($step->getReallocTeam() - 1);
                $team->setStatus($position);
                $em->flush($team);
                $em->flush($step);
            }
            return $this->redirectToRoute('mission_teams_selection', array(
                'missionId' => $missionId
            ));
        }
        return $this->render('MissionBundle:Mission:takeback.html.twig', array(
            'form' => $form->createView(),
            'missionId' => $missionId
            ));
    }

    public function deleteAction($missionId)
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $service = $this->container->get('team');
        $repository = $em->getRepository('MissionBundle:Mission');
        $mission = $repository->find($missionId);
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')
            && $mission != null && $service->isContractorOfMission($this->getUser(), $mission) == true
            && $mission->getStatus() >= 0)
        {
            $mission->setStatus(-1);
            $repository = $em->getRepository('TeamBundle:Team');
            $teams = $repository->getAdvisorsTeams($missionId);
            foreach ($teams as $team) {
                $team->setStatus(-2);
            }
            $em->flush();
            return $this->redirectToRoute('missions_all', array());
        }
        throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', array(), 'MissionBundle'));
    }

    public function pitchAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('MissionBundle:Mission');
        $user = $this->getUser();
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        if ( $user === null ) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR'))
        {
            $listTeams = $repository->getTeamsByUserId($user->getId());
            return $this->render('MissionBundle:Mission:pitch_all.html.twig', array(
                'listTeams'           => $listTeams
            ));
        }
        else {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        }
    }

    public function giveUpAction($missionId)
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('MissionBundle:Mission')->findOneById($missionId);

        $user = $this->getUser();
        $repository = $em->getRepository('TeamBundle:Team');
        $team = $repository->getTeamByMissionAndUser($missionId, $user->getId());

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
            && $mission != null && $team != null && $mission->getStatus() >= 1)
        {
            $listUsers = $team->getUsers();
            $team->removeUser($user);
            $user->setGiveUpCount($user->getGiveUpCount() + 1);
            $em->persist($team);
            $em->flush();
            $listUsers = $team->getUsers();

            if (count($listUsers) == 0) {
                $team->setStatus(-3);
            }
            $em->flush();
            return $this->redirectToRoute('dashboard', array());
        }
        throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', array(), 'MissionBundle'));
    }
}
