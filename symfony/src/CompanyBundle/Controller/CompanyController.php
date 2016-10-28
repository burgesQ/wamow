<?php

namespace CompanyBundle\Controller;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CompanyBundle\Entity\Company;
use CompanyBundle\Form\CompanyType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



class CompanyController extends Controller
{
  /**
   * @Security("has_role('ROLE_CONTRACTOR')")
   */
  public function createAction(Request $request)
    {
      $company = new Company();

      $form = $this->get('form.factory')->createBuilder('form', $company)
      ->add('name',      'text')
      ->add('size',      'text')
      ->add('logo',      'text')
      ->add('type',      'text')
      ->add('resume',    'textarea')
      ->add('sector',  EntityType::class, array(
            'class' => 'CompanyBundle:Sector',
            'choice_label' => 'name',))
      ->add('save',      'submit')
      ->getForm()
      ;
      $form->handleRequest($request);

      if ($form->isValid()) {
      $company  = $form->getData();
      $em = $this->getDoctrine()->getManager();

      $exist = $em
         ->getRepository('CompanyBundle:Company')
         ->findOneByName($company->getName())
         ;
      if ($exist != null)
      {
        throw new NotFoundHttpException("The company ".$company->getName()." already exist.");
      }
      $user = $this->getUser();
      $user->setCompany($company);

      $em->persist($company);
      $em->persist($user);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Company successfully registered');
      return $this->render('CompanyBundle:Default:created.html.twig', array('company' => $company));
      }

      return $this->render('CompanyBundle:Default:create.html.twig', array(
                           'form' => $form->createView(),));
      }

  /**
   * @Security("has_role('ROLE_CONTRACTOR')")
   */
  public function joinAction(Request $request)
   {
     $data = array();
     $form = $this->createFormBuilder($data)
     ->add('name', 'entity', array(
     'class' => 'CompanyBundle:Company',
     'property' => 'name',))
     ->add('save',      'submit')
     ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
    $data = $form->getData();
    $company = $data['name'];

    $user = $this->getUser();
    $user->setCompany($company);

    $em = $this->getDoctrine()->getManager();
    $em->persist($company);
    $em->persist($user);

    $em->flush();

    $request->getSession()->getFlashBag()->add('notice', 'Joined the company');
    return $this->render('CompanyBundle:Default:joined.html.twig', array('company' => $company));
    }

    return $this->render('CompanyBundle:Default:join.html.twig', array(
           'form' => $form->createView(),));
    }

  public function showAction($id)
  {
    $em = $this->getDoctrine()->getManager();

    $company = $em
     ->getRepository('CompanyBundle:Company')
     ->find($id)
     ;

     if (null === $company) {
       throw new NotFoundHttpException("The company Id ".$id." doesn't exist.");
     }

   $contractors = $em
     ->getRepository('UserBundle:User')
     ->findBy(array('company' => $company))
   ;

   return $this->render('CompanyBundle:Default:show.html.twig', array(
     'company'           => $company,
     'contractors' => $contractors
   ));
    }
}
