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
    public function advisorAction()
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

        return $this->render('HomePageBundle:Advisor:home.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'home' => 1
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contractorAction(Request $request)
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
                    'phone' => $form->get('phone')->getData()
//                    de-mod once form okay
                ]), 'text/html');
            $this->get('mailer')->send($message);

            $request->getSession()->getFlashBag()->add('notice', $trans->trans('home.contractor.preregister.registered', [], 'tools'));
            return $this->redirectToRoute('home_page_contractor');
        }
        return $this->render('HomePageBundle:Contractor:home.html.twig', array(
            'form' => $form->createView(),
            'home' => 1
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function noLocalAction()
    {
        // her we'll find a way to define the local of the user
        return $this->redirectToRoute('home_page_advisor', ['_locale' => 'en']);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function noLocalNewsletterAction()
    {
        // her we'll find a way to define the local of the user
        return $this->redirectToRoute('newsletter_list', ['_locale' => 'en']);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function dummySignUpAction()
    {
        // her we'll find a way to define the local of the user
        return $this->redirectToRoute('newsletter_list', ['_locale' => 'en']);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function dummyContactAction()
    {
        // her we'll find a way to define the local of the user
        return $this->redirectToRoute('home_page_advisor', ['_locale' => 'en']);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function termsAction()
    {
        return $this->render('@HomePage/terms.html.twig', [
            'user' => $this->getUser(),
            'home' => 1
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAction()
    {
        return $this->render('@HomePage/about.html.twig', [
            'user' => $this->getUser(),
            'home' => 1
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function helpAction()
    {
        return $this->render('@HomePage/help.html.twig', [
            'user' => $this->getUser(),
            'home' => 1,
            'footer' => 1
        ]);
    }
}
