<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use UserBundle\Form\RegistrationAdvisor\MergedFormRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Form\RegistrationAdvisor\StepThreeType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use UserBundle\Form\RegistrationAdvisor\StepFourType;
use UserBundle\Form\RegistrationAdvisor\StepOneType;
use UserBundle\Form\RegistrationAdvisor\StepTwoType;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use ToolsBundle\Entity\UploadResume;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use ToolsBundle\Entity\Address;
use UserBundle\Entity\User;

class RegistrationAdvisorController extends Controller
{
    /**
     * manage signUp Step0 Expert
     *
     * @param Request $request
     *
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepZeroAction(Request $request)
    {
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher  = $this->container->get('event_dispatcher');
        $session     = new Session(new PhpBridgeSessionStorage());
        $userManager = $this->get('fos_user.user_manager');
        $em          = $this->getDoctrine()->getManager();

        $session->start();
        $session->set('role', 'ADVISOR');

        if (!($user = $this->getUser())) {
            /** @var \UserBundle\Entity\User $user */
            $user = $userManager->createUser();
            $user->setEnabled(true);
            $user->setRoles(["ROLE_ADVISOR"]);
        } else if (($url = $this->get('signed_up')->checkIfSignedUp($user->getStatus()))) {
            $this->redirectToRoute($url);
        }

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        // create Resume entity
        $resume = new UploadResume($user);
        $resume->setKind(2);

        // create a Address entity
        if ($user->getAddresses()->isEmpty()) {
            $address = new Address();
            $user->addAddress($address);
            $em->persist($address);
        }

        // create merged form
        $formData['user']   = $user;
        $formData['resume'] = $resume;
        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->createForm(new MergedFormRegistrationType(), $formData);
        $form->setData($user)->setData($resume);

        // if form submitted
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            if (!$user->getLinkedinId() && !$form->get('resume')->getData()->getFile()) {
                $form->get('resume')->addError(
                    new FormError('Please Upload a resume or link your account with Linkedin')
                );
            } else {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
                $user->setFirstName(ucwords($user->getFirstName()));
                $user->setLastName(ucwords($user->getLastName()));
                $user->setStatus(User::REGISTER_STEP_ONE);
                $userManager->updateUser($user);
                $user->setPublicId(md5(uniqid() . $user->getUserResume() . $user->getId()));
                $resume->setUser($user);
                $em->persist($resume);
                $em->flush();
                if (null === $response = $event->getResponse()) {
                    $response = new RedirectResponse($this->generateUrl('expert_registration_step_one'));
                }
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED,
                    new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return $this->render('UserBundle:Registration:register_expert.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * manage signUp Step1 Expert
     *
     * @param Request $request
     *
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepOneAction(Request $request)
    {

        if (!($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_registration_register_expert');
        } elseif ($user->getStatus() !== User::REGISTER_STEP_ONE) {
            return $this->redirectToRoute($this->get('signed_up')->checkIfSignedUp($user->getStatus()));
        }

        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->createForm(new StepOneType(), $user)->setData($user);
        // if form submitted
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            // if back button submit, reset user data to previous step
            if ($form->get('back')->isClicked()) {
                $user->setStatus(User::REGISTER_STEP_ZERO);
                $this->get('fos_user.user_manager')->updateUser($user);

                return new RedirectResponse($this->redirectToRoute('expert_registration_step_one'));
            }
            $user->setStatus(User::REGISTER_STEP_TWO);
            $this->get('fos_user.user_manager')->updateUser($user);

            return $this->redirectToRoute('expert_registration_step_two');
        }

        return $this->render('UserBundle:Registration:register_expert_step_one.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * manage signUp Step2 Expert
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepTwoAction(Request $request)
    {
        if (!($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_registration_register_expert');
        } elseif ($user->getStatus() !== User::REGISTER_STEP_TWO) {
            return $this->redirectToRoute($this->get('signed_up')->checkIfSignedUp($user->getStatus()));
        }

        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->createForm(new StepTwoType(), $user)->setData($user);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            if ($form->get('back')->isClicked()) {
                $user->setStatus(User::REGISTER_STEP_ONE);
                $this->get('fos_user.user_manager')->updateUser($user);

                return $this->redirectToRoute('expert_registration_step_one');
            }
            $user->setStatus(User::REGISTER_STEP_THREE);
            $this->get('fos_user.user_manager')->updateUser($user);

            return $this->redirectToRoute('expert_registration_step_three');
        }

        return $this->render('UserBundle:Registration:register_expert_step_two.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * manage signUp Step3 Expert
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepThreeAction(Request $request)
    {
        if (!($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_registration_register_expert');
        } elseif ($user->getStatus() !== User::REGISTER_STEP_THREE) {
            return $this->redirectToRoute($this->get('signed_up')->checkIfSignedUp($user->getStatus()));
        }

        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->createForm(new StepThreeType(), $user)->setData($user);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            if ($form->get('back')->isClicked()) {
                $user->setStatus(User::REGISTER_STEP_TWO);
                $this->get('fos_user.user_manager')->updateUser($user);

                return $this->redirectToRoute('expert_registration_step_two');
            }
            $user->setStatus(User::REGISTER_STEP_FOUR);
            $this->get('fos_user.user_manager')->updateUser($user);

            return $this->redirectToRoute('expert_registration_step_four');
        }

        return $this->render('UserBundle:Registration:register_expert_step_three.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * manage signUp Step4 Expert
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepFourAction(Request $request)
    {
        if (!($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_registration_register_expert');
        } elseif ($user->getStatus() !== User::REGISTER_STEP_FOUR) {
            return $this->redirectToRoute($this->get('signed_up')->checkIfSignedUp($user->getStatus()));
        }

        $em = $this->getDoctrine()->getManager();
        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->createForm(new StepFourType(), $user)->setData($user);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            if ($form->get('back')->isClicked()) {
                $user->setStatus(User::REGISTER_STEP_THREE);
                $this->get('fos_user.user_manager')->updateUser($user);

                return $this->redirectToRoute('expert_registration_step_three');
            }
            foreach ($user->getWorkExperience() as $oneWorkExp) {
                $em->persist($oneWorkExp);
            }
            $user->setStatus(User::REGISTER_NO_STEP);
            $this->get('fos_user.user_manager')->updateUser($user);
            $em->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('UserBundle:Registration:register_expert_step_four.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
