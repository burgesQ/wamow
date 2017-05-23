<?php

namespace HomePageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

use ToolsBundle\Form\PreregisterType;
use ToolsBundle\Entity\Preregister;

class DefaultController extends Controller
{
    public function expertAction(Request $request)
    {
        if ($this->getUser() !== null) {
            return $this->redirectToRoute('dashboard');
        }

        $session = new Session(new PhpBridgeSessionStorage());
        $session->start();
        $session->set('role', 'ADVISOR');

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
                 && ($url = $this->get('signed_up')->checkIfSignedUp($this->getUser()->getStatus()))) {
            return $this->redirectToRoute($url);
        }

        return $this->render('HomePageBundle:Expert:home.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
    public function seekerAction(Request $request)
    {
        $trans = $this->get('translator');

        $session = new Session(new PhpBridgeSessionStorage());
        $session->start();
        $session->set('role', 'CONTRACTOR');

        $preregister = new Preregister();
        $form = $this->get('form.factory')->create(new PreregisterType(), $preregister);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($preregister);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $trans->trans('preregister.registered', array(), 'tools'));
            unset($preregister);
            unset($form);
            $preregister = new Preregister();
            $form = $this->get('form.factory')->create(new PreregisterType(), $preregister);
        }
        return $this->render('HomePageBundle:Seeker:home.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
