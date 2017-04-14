<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use UserBundle\Entity\User;

class RegistrationController extends BaseController
{
    /**
     * Redirect to the correct registration step
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerExpertAction()
    {
        if (($user = $this->getUser()) &&
            (($url = $this->get('signedup')->checkIfSignedUp($user->getStatus())) !==  null)) {

            return $this->redirectToRoute($url);
        }
        elseif (!$user &&
                !$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            return $this->redirectToRoute('expert_registration_step_zero');
        }

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
