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
            while ($repository->findByToken($token) != null)
            {
                $token = bin2hex(random_bytes(10));
            }
            $mission = new Mission($nbStep, $user->getId(), 1, $token);
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
        else
        {
            throw new NotFoundHttpException("You're not authorized to create a mission.");
        }
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
        if ($this->container->get('security.authorization_checker')
                            ->isGranted('ROLE_CONTRACTOR'))
            {
                $iDContact = $user->getId();
                $listMission = $repository->findByiDContact($iDContact);
                return $this->render('MissionBundle:Mission:all_missions.html.twig', array(
                    'listMission'           => $listMission,
                    'role'                  => $role[0]
                    ));
            }
        elseif ($this->container->get('security.authorization_checker')
                                ->isGranted('ROLE_ADVISOR'))
            {
                $listMission = $repository->missionsAvailables();
                return $this->render('MissionBundle:Mission:all_missions.html.twig', array(
                    'listMission'           => $listMission,
                    'role'                  => $role[0]
                    ));
            }
        else
            {
                throw new NotFoundHttpException("Error : you are neither loged as advisor, nor contractor.");
            }
    }

    public function missionPitchAction()
    {
        // Team creations
        return new Response("Pitch done");
    }

}
