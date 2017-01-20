<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

class RegistrationController extends BaseController
{
    
    public function registerExpertAction(Request $request)
    {
        if (($user = $this->getUser()))
        {
            $step = $user->getStatus();
            $registrationService = $this->container->get('user.registration');

            if ($user->getUsername() == "" || $step == 42)
                return $this->container->get('user.registration')->manageStepZeroExpert($this, $request);
            else if ($step == 0)
                return $registrationService->manageStepOneExpert($this, $request, $user);
            else if ($step == 1)
                return $registrationService->manageStepTwoExpert($this, $request, $user);
            else if ($step == 2)
                return $registrationService->manageStepThreeExpert($this, $request, $user);
            else if ($step == 3)
                return $registrationService->manageStepFourExpert($this, $request, $user);
            else if ($step == 4)
                return $registrationService->manageStepFiveExpert($this, $request, $user);
        }
        else if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            return $this->container->get('user.registration')->manageStepZeroExpert($this, $request);

        return $this->redirectToRoute('dashboard');
    }

    public function registerSeekerAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $session = new Session(new PhpBridgeSessionStorage());
            $session->start();
            $session->set('role', 'CONTRACTOR');
            /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
            $formFactory = $this->get('fos_user.registration.form.factory');
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
            /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
            $dispatcher = $this->get('event_dispatcher');

            $user = $userManager->createUser();
            $user->setEnabled(true);
            $user->setRoles(array("ROLE_CONTRACTOR"));
            $user->setPasswordSet(true);

            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            $form = $formFactory->createForm();
            $form->setData($user);

            $form->handleRequest($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $user->setFirstName(ucwords($user->getFirstName()));
                $user->setLastName(ucwords($user->getLastName()));
                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            return $this->render('UserBundle:Registration:register_seeker.html.twig', array(
                'form' => $form->createView(),
            ));
        }
        return $this->redirectToRoute('user_profile_show');
    }
}
