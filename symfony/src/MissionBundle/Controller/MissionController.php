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
            $service = $this->container->get('mission.nbStep');
            $nbStep = $service->getMissionNbStep();
            $user = $this->getUser();
            $token = bin2hex(random_bytes(10));
            while ($repository->findByToken($token) != null) {
                $token = bin2hex(random_bytes(10));
            }
            $team = new Team(1); //role 1 = contractor
            $team->addUser($this->getUser());
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
                $em->persist($mission);
                for ($i=1; $i <= $nbStep; $i++)
                {
                    $array = $service->getMissionStep($i);
                    $step = new Step($array["nbMaxTeam"], $array["reallocTeam"]);
                    $step->setMission($mission);
                    $step->setPosition($i);
                    $em->persist($step);
                }
                $em->flush();
                $id = $mission->getId();
                return $this->render('MissionBundle:Mission:registered.html.twig', array(
                    'mission'  =>   $mission,
                    'id'       =>   $id,
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
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('MissionBundle:Mission')->find($id);
        $trans = $this->get('translator');
        if ( $this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            if ( null === $mission ) {
                throw new NotFoundHttpException($trans->trans('mission.error.wrongId', array('%id%' => $id), 'MissionBundle'));
            } elseif ( $this->getUser()->getId() !== $mission->getTeamContact()->getId() ) {
                throw new NotFoundHttpException($trans->trans('mission.error.notYourMission', array(), 'MissionBundle'));
            }

            $form = $this->get('form.factory')->create(new MissionType(), $mission);
            $form->handleRequest($request);
            $mission->setUpdateDate(new \DateTime());

            if ($form->isValid()) {
          		if ($_POST)
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
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $trans = $this->get('translator');
        $service = $this->container->get('mission.savedTeams');
        $mission = $em->getRepository('MissionBundle:Mission')->find($id);

        if ( $this->getUser() === null ) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        } elseif ($mission == null) {
            throw new NotFoundHttpException($trans->trans('mission.error.wrongId', array('%id%' => $id), 'MissionBundle'));
        }
        $listLanguage = $mission->getLanguages();
        if ( $this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $team = $mission->getTeamContact();
            if ($service->checkContractorInTeam($this->getUser(), $mission) == false) {
                throw new NotFoundHttpException($trans->trans('mission.error.wright', array(), 'MissionBundle'));
            }
            return $this->render('MissionBundle:Mission:view_seeker.html.twig', array(
                'mission'           => $mission,
                'listLanguage'      => $listLanguage));
        }
        elseif ( $this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR'))
        {
            if ($mission->getStatus() !== 1) {
                throw new NotFoundHttpException($trans->trans('mission.error.available', array('%id%' => $id), 'MissionBundle'));
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
        $repository = $em->getRepository('MissionBundle:Mission');
        $user = $this->getUser();
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        if ( $user === null ) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $listTeam = $repository->myMissions($user->getId());
            return $this->render('MissionBundle:Mission:all_missions_seeker.html.twig', array(
                'listTeam'           => $listTeam
            ));
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')) {
            $listMission = $repository->expertMissionsAvailables();
            return $this->render('MissionBundle:Mission:all_missions_expert.html.twig', array(
                'listMission'           => $listMission
            ));
        }
        else
        {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        }
    }

    public function missionPitchAction($missionId)
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        if ( $this->getUser() === null ) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        } elseif ( $this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR') ) {
            throw new NotFoundHttpException($trans->trans('mission.pitch.contractor', array(), 'MissionBundle'));
        }
        $repository = $em->getRepository('MissionBundle:Mission');
        $listTeams = $repository->myMissions($this->getUser()->getId());
        $mission = $repository->findOneBy(array('id' => $missionId));
        foreach ($listTeams as $team) {
            if ($team->getMission() == $mission) {
                return new Response($trans->trans('mission.pitch.twice', array(), 'MissionBundle'));
            }
        }
        if ($mission->getStatus() !== 1) {
            throw new NotFoundHttpException($trans->trans('mission.error.available', array('%id%' => $id), 'MissionBundle'));
        }
        $team = new Team(0);  //role 0 = advisor
        $step = $repository->getSpecificStep($missionId, 1)[0];
        $i = count($repository->setTeamsAvailables($missionId, $step));
        if ($i < $step->getnbMaxTeam())
            $team->setStatus(1);
        $team->setMission($mission);
        $team->addUser($this->getUser());
        $em->persist($team);
        $em->flush($team);
        return new Response($trans->trans('mission.pitch.done', array(), 'MissionBundle'));
    }

    /*
    ** Team Status :
    **     -1 : deleted teams
    **      0 : waiting teams
    **      1 : first step
    **      2 : second step
    **      3 : winning teaam
    */
    public function selectionAction(Request $request, $missionId)
    {
        $trans = $this->get('translator');
        $service = $this->container->get('mission.savedTeams');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('MissionBundle:Mission');
        $mission = $repository->find($missionId);
        if ($mission == null || $mission->getStatus() != 1) {
            throw new NotFoundHttpException($trans->trans('mission.error.advisor', array(), 'MissionBundle'));
        }

        if ( $this->getUser() === null ) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        } elseif ( $this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR') ) {
            throw new NotFoundHttpException($trans->trans('mission.error.advisor', array(), 'MissionBundle'));
        }

        $step = $repository->getCurrentStep($missionId);
        $step = $step[0];
        $positionStep = $step->getPosition();
        if ($repository->getSpecificStep($missionId, $step->getPosition() + 1) == null) {
            $team = $repository->teamsAvailables($missionId, $step)[0];
            return new Response($trans->trans('mission.selection.finish', array('%id%' => $team->getId()), 'MissionBundle'));
        }
        $teamToChoose = $repository->teamsAvailables($missionId, $step);
        if ($teamToChoose == null)
            return new Response($trans->trans('mission.selection.noTeam', array(), 'MissionBundle'));
        $nbTeamToChoose = count($teamToChoose);
        $service->setTeamsAvailables($teamToChoose, $positionStep);

        $form = $this->get('form.factory')->create(new SelectionType($missionId, $step));
        $form->handleRequest($request);
        if ($this->get('request')->getMethod() == 'POST')
        {
            $data = $form->getData();
            $nbChosen = count($data['team']);
            if ($form->get('save')->isClicked())
            {
                if ($nbChosen > $repository->getSpecificStep($missionId, $positionStep + 1)[0]->getNbMaxTeam() || $nbChosen == 0)
                {
                    $service->sendDeleteMessageInView($request, $nbChosen, $nbTeamToChoose, $repository->getSpecificStep($missionId, $positionStep + 1)[0], $trans);
                    return $this->redirectToRoute('mission_teams_selection', array(
                        'missionId' => $missionId
                    ));
                }
                $service->setSaveTeamStatus($data['team'], $step);
                $nextStep = $repository->getSpecificStep($missionId, $positionStep + 1)[0];
                $nextStep->setStatus(1);
                $nextStep->setStartDate(new \DateTime());
                $step->setEndDate(new \DateTime());
                $em->flush($step);
                $em->flush($nextStep);
                return $this->redirectToRoute('mission_teams_selection', array(
                    'missionId' => $missionId
                ));
            }
            if ($nbChosen > $step->getReallocCounter() || $nbTeamToChoose - $nbChosen <= 0 || $step->getReallocCounter() == 0)
            {
                $service->sendDeleteMessageInView($request, $nbChosen, $nbTeamToChoose, $step, $trans);
                return $this->redirectToRoute('mission_teams_selection', array(
                    'missionId' => $missionId
                    ));
            }
            $step->setUpdateDate(new \DateTime());
            $em->flush($step);
            $service->setDeletedTeam($data['team'], $step);
            $teamToChoose = $repository->teamsAvailables($missionId, $step);
            $service->setTeamsAvailables($teamToChoose, $step->getPosition());
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
        $repository = $em->getRepository('MissionBundle:Mission');
        $step = $repository->getSpecificStep($missionId, 2)[0];

        if ( $this->getUser() === null ) {
            throw new NotFoundHttpException($trans->trans('mission.error.logged', array(), 'MissionBundle'));
        } elseif ( $this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR') ) {
            throw new NotFoundHttpException($trans->trans('mission.error.advisor', array(), 'MissionBundle'));
        }

        $form = $this->get('form.factory')->create(new TakeBackType($missionId));
        $form->handleRequest($request);
        if ($step->getStatus() != 1) {
            return $this->redirectToRoute('mission_teams_selection', array(
                'missionId' => $missionId
                ));
        }
        if ($step->getReallocCounter() == 0)
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
            $team = $data['team'];
            $step->setReallocCounter($step->getReallocCounter() - 1);
            $team->setStatus($step->getPosition());
            $em->flush($team);
            $step->setUpdateDate(new \DateTime());
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

    public function deleteMissionAction($missionId)
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $service = $this->container->get('mission.savedTeams');
        $mission = $em->getRepository('MissionBundle:Mission')->find($missionId);
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')
            && $mission != null && $service->checkContractorInTeam($this->getUser(), $mission) == true)
        {
            $mission->setStatus(-1);
            $em->flush($mission);
            return $this->redirectToRoute('missions_all', array());
        }
        throw new NotFoundHttpException($trans->trans('mission.error.advisor', array(), 'MissionBundle'));
    }
}
