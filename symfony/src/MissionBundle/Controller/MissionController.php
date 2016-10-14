<?php

namespace MissionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MissionBundle\Entity\Step;
use MissionBundle\Entity\Mission;
use MissionBundle\Form\MissionType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MissionController extends Controller
{
    /*
      Create a new mission
    */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $service = $this->container->get('mission.nbStep');
        $nbStep = $service->getMissionNbStep();
        $user = $this->getUser();
        $mission = new Mission($nbStep, $user->getId());
        $form = $this->get('form.factory')->create(new MissionType(), $mission);
        $form->handleRequest($request);
      if ($form->isValid())
        {
          $em->persist($mission);
          for ($i=0; $i < $nbStep; $i++)
              {
                  $array = $service->getMissionStep($i);
                  $step = new Step($array["nbMaxTeam"], $array["reallocTeam"]);
                  $step->setMission($mission);
                  $step->setPosition($i + 1);
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

    /*
      Edit a mission which already exits
    */
    public function editAction($id, Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $mission = $em->getRepository('MissionBundle:Mission')->find($id);
      if (null === $mission)
        {
          throw new NotFoundHttpException("Mission nº".$id." doesn't exist.");
        }
      $form = $this->get('form.factory')->create(new MissionType(), $mission);
      $form->handleRequest($request);
      $mission->setUpdateDate(new \DateTime());
      if ($form->isValid())
        {
          $em->flush();
          return new Response('Mission updated successfully');
        }
      return $this->render('MissionBundle:Mission:edit.html.twig', array(
        'form' => $form->createView(),
        'mission' => $mission,
        ));
    }

    /*
      Display a mission
    */
    public function viewAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $mission = $em
        ->getRepository('MissionBundle:Mission')
        ->find($id)
          ;
      if (null === $mission)
        {
          throw new NotFoundHttpException("The mission ".$id." doesn't exist.");
        }
      $listLanguage = $mission->getLanguages();
      return $this->render('MissionBundle:Mission:view.html.twig', array(
        'mission'           => $mission,
        'listLanguage'      => $listLanguage,
        ));
    }

    public function missionListAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('MissionBundle:Mission')
            ;
        $user = $this->getUser();
        $role = $user->getRoles();
        if ($role[0] == "ROLE_CONTRACTOR")
            {
                $iDContact = $user->getId();
                $listMission = $repository->findByiDContact($iDContact);
                return $this->render('MissionBundle:Mission:all_missions.html.twig', array(
                    'listMission'           => $listMission,
                    'role'                  => $role[0]
                    ));
            }
        else
            {
                $listMission = $repository->missionsAvailables();
                return $this->render('MissionBundle:Mission:all_missions.html.twig', array(
                    'listMission'           => $listMission,
                    'role'                  => $role[0]
                    ));
            }
    }

    public function missionPitchAction()
    {
        return new Response("Pitch done");
    }
}
