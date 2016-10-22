<?php

namespace CompanyBundle\Controller;

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
      ->add('resume',    'textarea')
      ->add('sector',  EntityType::class, array(
            'class' => 'CompanyBundle:Sector',
            'choice_label' => 'name',))
      ->add('save',      'submit')
      ->getForm()
    ;
    $form->handleRequest($request);

      if ($form->isValid()) {
      $contractor = $this->getUser();
      $company->setContractors($contractor);
      $company->setType('no');
      $em = $this->getDoctrine()->getManager();
      $em->persist($company);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Company successfully registered');
      return $this->render('CompanyBundle:Default:index.html.twig', array('id' => $company->getId()));
    }

  return $this->render('CompanyBundle:Default:create.html.twig', array(
     'form' => $form->createView(),));
      }
  /**
   * @Security("has_role('ROLE_CONTRACTOR')")
   */
  public function joinAction(Request $request)
    {
      $form = $this->get('form.factory')->createBuilder('form')
        ->add('company',  EntityType::class, array(
              'class' => 'CompanyBundle:Company',
              'choice_label' => 'name',))
        ->getForm();
        return $this->render('CompanyBundle:Default:join.html.twig', array(
           'form' => $form->createView(),));
    }
}
