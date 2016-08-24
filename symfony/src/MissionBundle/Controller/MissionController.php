<?php

namespace MissionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use MissionBundle\Entity\Mission;
use MissionBundle\Form\MissionType;
use MissionBundle\Form\MissionEditType;
use MissionBundle\Entity\Language;
use MissionBundle\Form\LanguageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class MissionController extends Controller
{
    /*
      Create a new mission
    */
    public function newAction(Request $request)
    {
        $mission = new Mission();
        $mission->setCreationDate(new \DateTime());
        $form = $this->get('form.factory')->create(new MissionType(), $mission);
        if ($form->handleRequest($request)->isValid())
        {
          $em = $this->getDoctrine()->getManager();
          $em->persist($mission);
          $em->flush();

          $id = $mission->getid();

          $request->getSession()->getFlashBag()->add('notice', 'Mission save');
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

      if (null === $mission) {
              throw new NotFoundHttpException("Mission nº".$id." doesn't exist.");
      }
      $form = $this->createFormBuilder($mission)
          ->add('title')
          ->add('resume')
          ->add('adress')
          ->add('city')
          ->add('country', 'country')
          ->add('zipcode')
          ->add('minNumberUser')
          ->add('maxNumberUser')
          ->add('confidentiality', 'checkbox', array(
            'label'    => 'Does this mission has to be confidential?',
            'required' => false,
          ))
          ->add('numberStep')
          ->add('state')
          /*->add('language', 'entity', array(
            'class' => 'MissionBundle:Language',
            'property' => 'name',
            'multiple' => true,
            'expanded' => true
          ))*/
          ->add('telecommuting', 'checkbox', array(
          'label'    => 'Does this mission propose telecommuting?',
          'required' => false,
          ))
          ->add('dailyFeesMin')
          ->add('dailyFeesMax')
          ->add('duration')
          ->add('beginning', 'date')
          ->add('image')
          ->add('save',      'submit')
          ->getForm();

          $form->handleRequest($request);
          $mission->setUpdateDate(new \DateTime());

      if ($form->isValid()) {
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

      if (null === $mission) {
        throw new NotFoundHttpException("The mission ".$id." doesn't exist.");
          }

      $listLanguage = $mission->getLanguage();

      return $this->render('MissionBundle:Mission:view.html.twig', array(
        'mission'           => $mission,
        'listLanguage'      => $listLanguage,
      ));
    }
}
