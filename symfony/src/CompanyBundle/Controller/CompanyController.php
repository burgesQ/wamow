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
        $trans = $this->get('translator');
        
        $form = $this->get('form.factory')->createBuilder('form', $company)
              ->add('name',      'text')
              ->add('size')
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

            $tmp = $em
                   ->getRepository('CompanyBundle:Company')
                   ->findOneByName($company->getName())
                   ;
            if ($tmp != null)
            {
                throw new NotFoundHttpException($trans->trans('company.error.usedId', array('%name%' => $company->getName()), 'CompanyBundle'));
            }
            $user = $this->getUser();
            $user->setCompany($company);

            $em->persist($company);
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $trans->trans('company.action.registered', array(), 'CompanyBundle'));
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
        $trans = $this->get('translator');

        if ( $this->getUser()->getCompany() ) { // if we remove it we have to check that the user isn't already in the company 
            throw new NotFoundHttpException($trans->trans('company.error.oneCompany', array(), 'CompanyBundle'));
        }
       
        $data = array();
        $form = $this->createFormBuilder($data)
              ->add('name', 'entity', array(
                  'class' => 'CompanyBundle:Company',
                  'property' => 'name',
                  'placeholder' => 'Create a new company ...',
                  'multiple' => false,
                  'required' => false,
                  'attr' => array('class' => 'chosen-select',
                                  'style' => 'width: 350px')))
              ->add('save',  'submit')
              ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $company = $data['name'];
            if ($company == null){
                return $this->redirectToRoute('company_create');
            }
            $user = $this->getUser();
            $user->setCompany($company);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->persist($user);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $trans->trans('company.info.join', array(), 'CompanyBundle'));
            return $this->render('CompanyBundle:Default:joined.html.twig', array('company' => $company));
        }

        return $this->render('CompanyBundle:Default:join.html.twig', array(
            'form' => $form->createView() ));
    }


  /**
   * @Security("has_role('ROLE_CONTRACTOR')")
   */
    public function leaveAction(Request $request)
    {
        $data = array();
        $form = $this->createFormBuilder($data)
              ->add('save',      'submit')
              ->getForm();

        $user = $this->getUser();
        $company = $user->getCompany();

        $form->handleRequest($request);
        if ($form->isValid())
        {
            $user->setCompany(NULL);
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->persist($user);
            $em->flush();

            return $this->render('CompanyBundle:Default:leaved.html.twig', array(
                'company'           => $company,));
        }
        return $this->render('CompanyBundle:Default:leave.html.twig', array(
            'form' => $form->createView(),));
    }

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $trans = $this->get('translator');
        
        $company = $em
                 ->getRepository('CompanyBundle:Company')
                 ->find($id)
                 ;

        if (null === $company) {
            throw new NotFoundHttpException($trans->trans('company.error.wrongId', array('%id%' => $id), 'CompanyBundle'));
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
