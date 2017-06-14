<?php

namespace HomePageBundle\Controller;

use Swift_Message;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use ToolsBundle\Form\PreregisterType;
use ToolsBundle\Entity\Preregister;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function expertAction()
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

        return $this->render('HomePageBundle:Expert:home.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function seekerAction(Request $request)
    {
        $trans       = $this->get('translator');
        $preregister = new Preregister();
        $session     = new Session(new PhpBridgeSessionStorage());
        $session->start();
        $session->set('role', 'CONTRACTOR');
        $form = $this->get('form.factory')->create(new PreregisterType(), $preregister);

        if ($form->handleRequest($request) && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($preregister);
            $em->flush();

            $trans = $this->get('translator');
            $message = Swift_Message::newInstance()
                ->setSubject('New Contractor Pre-Registred')
                ->setFrom($this->container->getParameter('email_sender'))
                ->setTo($this->container->getParameter('email_pre_register'))
                ->setBody($this->renderView('Emails/new_pre_register.html.twig', [
                    'preRegister'   => $preregister,
                ]), 'text/html');
            $this->get('mailer')->send($message);

            $request->getSession()->getFlashBag()->add('notice', $trans->trans('home.contractor.preregister.registered', [], 'tools'));
            return $this->redirectToRoute('home_page_seeker');
        }
        return $this->render('HomePageBundle:Seeker:home.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
