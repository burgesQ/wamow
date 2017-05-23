<?php
namespace CompanyBundle\Controller;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CompanyBundle\Entity\Company;
use CompanyBundle\Form\CompanyType;
use CompanyBundle\Form\CompanyLeaveType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CompanyController extends Controller
{
    public function createAction(Request $request)
    {
        $trans = $this->get('translator');
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            if ($this->getUser()->getCompany()) {
                $request->getSession()->getFlashBag()->add('notice', $trans->trans('company.new.error.onecompany', array(), 'CompanyBundle'));
                return $this->redirectToRoute('dashboard');
            }

            $company = new Company();
            $form = $this->get('form.factory')->create(CompanyType::class, $company);
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
                    $request->getSession()->getFlashBag()->add('notice', $trans->trans('company.new.error.usedname', array(), 'CompanyBundle'));
                    return $this->redirectToRoute('company_join');
                }
                $user = $this->getUser();
                $user->setCompany($company);
                $em->persist($company);
                $em->persist($user);
                $em->flush();

                // Add notification for contractor
                $param = array(
                    'company' => $company->getId(),
                    'user'    => $user->getId(),
                );
                $notification = $this->container->get('notification');
                $notification->new($this->getUser(), 1, 'notification.seeker.company.join', $param);

                $request->getSession()->getFlashBag()->add('notice', $trans->trans('company.new.registered', array('name' => $company->getName()), 'CompanyBundle'));
                return $this->redirectToRoute('dashboard');
            }
            return $this->render('CompanyBundle:Company:create.html.twig', array(
                'form' => $form->createView(),));
        }
        $request->getSession()->getFlashBag()->add('notice', $trans->trans('company.new.error.authorized', array(), 'CompanyBundle'));
        return $this->redirectToRoute('dashboard');
    }

    public function joinAction(Request $request)
    {
        $trans = $this->get('translator');
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            if ($this->getUser()->getCompany()) {
                $request->getSession()->getFlashBag()->add('notice', $trans->trans('company.join.error.onecompany', array(), 'CompanyBundle'));
                return $this->redirectToRoute('dashboard');
            }

            $data = array();
            $form = $this->createFormBuilder($data)
                  ->add('name', 'entity', array(
                      'class' => 'CompanyBundle:Company',
                      'translation_domain' => 'CompanyBundle',
                      'property' => 'name',
                      'placeholder' => 'company.join.new',
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
                $em->persist($user);
                $em->flush();

                // Add notification for contractor
                $param = array(
                    'company' => $company->getId(),
                    'user'    => $user->getId(),
                );
                $notification = $this->container->get('notification');
                $notification->new($this->getUser(), 1, 'notification.seeker.company.join', $param);

                $request->getSession()->getFlashBag()->add('notice', $trans->trans('company.join.joined', array('name' => $company->getName()), 'CompanyBundle'));
                return $this->redirectToRoute('dashboard');
            }
            return $this->render('CompanyBundle:Company:join.html.twig', array(
                'form' => $form->createView() ));
        }
        $request->getSession()->getFlashBag()->add('notice', $trans->trans('company.join.error.authorized', array(), 'CompanyBundle'));
        return $this->redirectToRoute('dashboard');
    }

    public function leaveAction(Request $request)
    {
        $trans = $this->get('translator');
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            if ( !$this->getUser()->getCompany() ) {
                $request->getSession()->getFlashBag()->add('notice', $trans->trans('company.leave.error.nocompany', array(), 'CompanyBundle'));
                return $this->redirectToRoute('company_join');
            }

            $user = $this->getUser();
            $company = $user->getCompany();
            $form = $this->get('form.factory')->create(CompanyLeaveType::class);
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $user->setCompany(NULL);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                // Add notification for contractor
                $param = array(
                    'company' => $company->getId(),
                    'user'    => $user->getId(),
                );
                $notification = $this->container->get('notification');
                $notification->new($this->getUser(), 1, 'notification.seeker.company.leave', $param);

                $request->getSession()->getFlashBag()->add('notice', $trans->trans('company.leave.leaved', array('name' => $company->getName()), 'CompanyBundle'));
                return $this->redirectToRoute('dashboard');
            }
            return $this->render('CompanyBundle:Company:leave.html.twig', array(
                'form' => $form->createView(),
                'company' => $company,
            ));
        }
        $request->getSession()->getFlashBag()->add('notice', $trans->trans('company.leave.error.authorized', array(), 'CompanyBundle'));
        return $this->redirectToRoute('dashboard');
    }
}

