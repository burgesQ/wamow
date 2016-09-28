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
use MissionBundle\Form\StepType;
use ToolsBundle\Entity\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MissionController extends Controller
{
    /*
      Create a new mission
    */
    public function newAction(Request $request)
    {
      $mission = new Mission();
      $form = $this->get('form.factory')->create(new MissionType(), $mission);
      $form->handleRequest($request);
      if ($form->isValid())
        {
          $em = $this->getDoctrine()->getManager();
          $em->persist($mission);
          for ($i=0; $i < $mission->getNumberStep(); $i++)
              {
                  $step = new Step();
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

    /*
      Add step to a mission
    */
    public function stepAction($id, Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $mission = $em->getRepository('MissionBundle:Mission')->find($id);
      $step = new Step();
      $step->setMission($mission);
      if (null === $mission)
        {
          throw new NotFoundHttpException("The mission ".$id." doesn't exist.");
        }
      $form = $this->get('form.factory')->create(new StepType(), $step);
      $listStep = $em
      ->getRepository('MissionBundle:Step')
      ->findBy(array('mission' => $mission))
      ;
      $nbStep = 0;
      foreach ($listStep as $step)
        {
          ++$nbStep;
        }
      if (!$listStep)    //If there's no step yet
        {
          if ($form->handleRequest($request)->isValid())
            {
              $em = $this->getDoctrine()->getManager();
              $em->persist($step);
              $em->flush();
              $id = $mission->getid();
              return $this->render('MissionBundle:Mission:stepdone.html.twig', array(
                'mission'  =>   $mission,
                'id'       =>   $id,
                'form'     =>   $form,
                'step'     =>   $step
                ));
            }
          return $this->render('MissionBundle:Mission:step.html.twig', array(
            'mission'  => $mission,
            'step'     => $step,
            'form'     => $form->createView()
            ));
        }
      else if ($mission->getNumberStep() == $nbStep)  //If it's the last step
      {
        return new Response("Last step");
      }
      else                            //Creation of new step
      {
        $step = new Step();
        $step->setMission($mission);
        $step->setPosition($nbStep + 1);
        if ($form->handleRequest($request)->isValid())
          {
            $em = $this->getDoctrine()->getManager();
            $em->persist($step);
            $em->flush();
            $id = $mission->getid();
            return $this->render('MissionBundle:Mission:stepdone.html.twig', array(
              'mission'  =>   $mission,
              'id'       =>   $id,
              'form'     =>   $form,
              'step'     =>   $step
            ));
          }
        return $this->render('MissionBundle:Mission:step.html.twig', array(
          'mission'  => $mission,
          'step'     => $step,
          'form'     => $form->createView(),
          'nbStep'   => $nbStep
          ));
      }
    }
}
