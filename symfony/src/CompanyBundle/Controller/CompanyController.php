<?php

namespace CompanyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CompanyBundle\Entity\Company;
use CompanyBundle\Form\CompanyType;




use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CompanyController extends Controller
{
  public function createAction(Request $request)
    {
    // $user = $this->getUser();
    // $company = new Company();
    //
    // $form = $this->get('form.factory')->create(new CompanyType(), $company);
    // $form->handleRequest($request);
    // if ($form->isValid())
    //     {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($company);
    //         $em->flush();
    //         $id = $mission->getId();
    //         return $this->render('CompanyBundle:Company:registered.html.twig', array(
    //         'mission'  =>   $company,
    //         'form'     =>   $form,
    //         ));
    //     }
      return $this->render('CompanyBundle:Default:index.html.twig');//, array(
    //     'form' => $form->createView(),
    //     ));
      }
}
