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
        $mission = new Mission($nbStep);
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

    public function allmissionAction()
        {
            $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('MissionBundle:Mission')
                ;
            $listMission = $repository->available();
            return $this->render('MissionBundle:Mission:all_missions.html.twig', array(
                'listMission'           => $listMission
                ));
        }

    public function missionByContractorAction()
        {
            $iDContact = 4; // fake ID to test, real ID available with User identification
            $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('MissionBundle:Mission')
                ;
            $listMission = $repository->findByiDContact($iDContact);
            return $this->render('MissionBundle:Mission:all_missions.html.twig', array(
                'listMission'           => $listMission
                ));
        }
}
