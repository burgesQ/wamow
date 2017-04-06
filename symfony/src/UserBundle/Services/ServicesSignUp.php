<?php

namespace UserBundle\Services;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use MissionBundle\Entity\ExperienceShaping;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use ToolsBundle\Entity\UploadResume;
use UserBundle\Form\MergedFormRegistrationType;
use UserBundle\Form\StepFiveType;
use UserBundle\Form\StepFourType;
use UserBundle\Form\StepOneType;
use UserBundle\Form\StepThreeType;
use UserBundle\Form\StepTwoType;

class ServicesSignUp
{

    /**
     * manage signUp Step0 Expert
     *
     * @param         $context
     * @param Request $request
     *
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function manageStepZeroExpert($context, Request $request)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $context->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $context->get('event_dispatcher');

        $session = new Session(new PhpBridgeSessionStorage());
        $session->start();
        $session->set('role', 'ADVISOR');

        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user->setRoles(array("ROLE_ADVISOR"));
        $user->setPasswordSet(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse())
            return $event->getResponse();

        // create Resume entity
        $resume = new UploadResume($user);
        $resume->setKind(2)->setFormat("pdf");

        // create merged form
        $formData['user'] = $user;
        $formData['resume'] = $resume;
        $form = $context->createForm(new MergedFormRegistrationType(), $formData);
        $form->setData($user);
        $form->setData($resume);
        $form->handleRequest($request);

        // if form submitted
        if ($form->isSubmitted() && $form->isValid())
        {
            $valid = true;
            $event = new FormEvent($form, $request);

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            // if country not submitted
            if (!$user->getCountry()) {
                $form->get('user')->get('country')
                    ->addError(new FormError("Please select a residency country"));
                $valid = false;
            } elseif (!$resume->getFile()) { // elif resume not submitted
                $form->get('resume')->get('file')
                    ->addError(new FormError("Please upload a resume"));
                $valid = false;
            } elseif (!$user->getEmail()) { // elif email not submitted
                $form->get('email')
                    ->addError(new FormError("Please upload a email"));
                $valid = false;
            } else { // else save user data
                $user->setFirstName(ucwords($user->getFirstName()));
                $user->setLastName(ucwords($user->getLastName()));
                $user->setStatus(0);
                $userManager->updateUser($user);
                $em = $context->getDoctrine()->getManager();
                $resume->setUser($user);
                $em->persist($resume);
                    $em->flush();
            }

            // if user correctly uploaded datas
            if ($valid)
            {
                if (null === $response = $event->getResponse())
                    $response = new RedirectResponse($context->generateUrl('expert_registration_step_one'));
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED,
                    new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return $context->render('UserBundle:Registration:register_expert.html.twig', [
                'form' => $form->createView(),
        ]);
    }

    /**
     * manage signUp Step1 Expert
     *
     * @param         $context
     * @param Request $request
     * @param         $user
     *
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function manageStepOneExpert($context, Request $request, $user)
    {
        // tcheck if email address not already in use
        if ($user->getEmail() == "")
            return $this->manageStepZeroExpert($context, $request, $user);

        // create form
        $form = $context->createForm(new StepOneType(), $user);
        $form->setData($user);
        $form->handleRequest($request);

        // if form submitted
        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $context->get('fos_user.user_manager');
            $event = new FormEvent($form, $request);

            // update user datas
            $user->setStatus(1);
            $userManager->updateUser($user);

            // pass next step
            if (null === $response = $event->getResponse())
            {
                $url = $context->generateUrl('expert_registration_step_two');
                $response = new RedirectResponse($url);
            }
            return $response;
        }

        return $context->render('UserBundle:Registration:register_expert_step_one.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * manage signUp Step2 Expert
     */
    public function manageStepTwoExpert($context, Request $request, $user)
    {
        // create form
        $form = $context->createForm(new StepTwoType(), $user);
        $form->setData($user);
        $form->handleRequest($request);

        // if form is submitted
        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $context->get('fos_user.user_manager');
            $event = new FormEvent($form, $request);

            // if back button submit, reset user data to previous step
            if ($form->get('back')->isClicked())
            {
                $user->setStatus(0);
                $userManager->updateUser($user);
                return new RedirectResponse($context->generateUrl('expert_registration_step_one'));
            }

            // update user datas
            $user->setStatus(2);
            $userManager->updateUser($user);

            // pass next step
            if (null === $response = $event->getResponse())
            {
                $url = $context->generateUrl('expert_registration_step_three');
                $response = new RedirectResponse($url);
            }
            return $response;
        }

        return $context->render('UserBundle:Registration:register_expert_step_two.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * manage signUp Step3 Expert
     */
    public function manageStepThreeExpert($context, Request $request, $user)
    {
        // create form
        $form = $context->createForm(new StepThreeType(), $user);
        $form->setData($user);
        $form->handleRequest($request);

        // if form is submitted
        if ($form->isValid())
        {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $context->get('fos_user.user_manager');
            $event = new FormEvent($form, $request);

            // if back button submit, reset user data to previous step
            if ($form->get('back')->isClicked())
            {
                $user->setStatus(1);
                $userManager->updateUser($user);
                return new RedirectResponse($context->generateUrl('expert_registration_step_two'));
            }

            // update user datas
            $user->setStatus(3);
            $userManager->updateUser($user);

            // pass next step
            if (null === $response = $event->getResponse())
            {
                $url = $context->generateUrl('expert_registration_step_four');
                $response = new RedirectResponse($url);
            }
            return $response;
        }

        return $context->render('UserBundle:Registration:register_expert_step_three.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * manage signUp Step4 Expert
     */
    public function manageStepFourExpert($context, Request $request, $user)
    {
        // create form
        $form = $context->createForm(new StepFourType(), $user);
        $form->setData($user);
        $form->handleRequest($request);

        // if form is submitted
        if ($form->isValid())
        {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $context->get('fos_user.user_manager');
            $event = new FormEvent($form, $request);

            // if back button submit, reset user data to previous step
            if ($form->get('back')->isClicked())
            {
                $user->setStatus(2);
                $userManager->updateUser($user);
                return new RedirectResponse($context->generateUrl('expert_registration_step_three'));
            }

            // update user datas
            $user->setStatus(4);
            $userManager->updateUser($user);

            // pass next step
            if (null === $response = $event->getResponse())
            {
                $url = $context->generateUrl('expert_registration_step_five');
                $response = new RedirectResponse($url);
            }
            return $response;
        }

        return $context->render('UserBundle:Registration:register_expert_step_four.html.twig',
            [
                'form' => $form->createView()
            ]
        );
     }

    /**
     * manage signUp Step5 Expert
     */
    public function manageStepFiveExpert($context, Request $request, $user)
    {

        $domains = [];

        // create entity and get their names
        foreach ($user->getWorkExperience() as $domain) {
            $experienceShaping = new ExperienceShaping($domain);
            $user->addExperienceShaping($experienceShaping);
            array_push($domains, $domain);
        }

        // create embedform
        $form = $context->createForm(new StepFiveType(), $user);
        $form->handleRequest($request);

        // if form is submitted
        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $context->get('fos_user.user_manager');
            $em = $context->getDoctrine()->getManager();
            $event = new FormEvent($form, $request);

            // if back button submit, reset user data to previous step
            if ($form->get('back')->isClicked())
            {
                $user->setStatus(3);
                $user->resetWorkExperience();
                $userManager->updateUser($user);
                return new RedirectResponse($context->generateUrl('expert_registration_step_four'));
            }

            // update user datas
            foreach ($user->getExperienceShaping() as $oneExperience)
                $em->persist($oneExperience);
            $em->flush();
            $user->setStatus(5);
            $userManager->updateUser($user);

            // save user
            $parser = $context->get('user.services');
            $parser->parseResume($context->getDoctrine()->getRepository('ToolsBundle:UploadResume'),
                                 $user, $context->getDoctrine()->getManager());

            // pass user next step
            if (null === $response = $event->getResponse()) {
                $url = $context->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse(  ($user->getPasswordSet()) ? $url : '/dashboard');
            }
            return $response;
        }

        return $context->render('UserBundle:Registration:register_expert_step_five.html.twig', [
                'form' => $form->createView(),
                'domains' => $domains
        ]);
    }

};
