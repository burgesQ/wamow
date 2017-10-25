<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Controller\ResettingController as BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;

class ResettingController extends BaseController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function checkEmailAction(Request $request)
    {
        $userName = $request->query->get('username');

        if (empty($userName)) {
            return new RedirectResponse($this->generateUrl('fos_user_resetting_request'));
        }

        return $this->render('UserBundle:Resetting:check_email.html.twig', [
            'email' => $userName
        ]);
    }

    /**
     * Request reset user password: show form.
     */
    public function requestAction()
    {
        return $this->render('@FOSUser/Resetting/request.html.twig');
    }

    /**
     * Request reset user password: submit form and send email.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function sendEmailAction(Request $request)
    {
        $username = $request->request->get('username');

        /** @var $user \UserBundle\Entity\User */
        $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        /* Dispatch init event */
        $event = new GetResponseNullableUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $ttl = $this->container->getParameter('fos_user.resetting.retry_ttl');

        if (null !== $user && !$user->isPasswordRequestNonExpired($ttl)) {
            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_REQUEST, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            if (null === $user->getConfirmationToken()) {
                /** @var $tokenGenerator TokenGeneratorInterface */
                $tokenGenerator = $this->get('fos_user.util.token_generator');
                $user->setConfirmationToken($tokenGenerator->generateToken());
            }

            /* Dispatch confirm event */
            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_CONFIRM, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            $this->get('wamow.mailer')->sendWamowMails(
                'mails.subject.reset_password',
                $user->getEmail(),
                'Emails/reset_password.html.twig', [
                'f_name' => $user->getFirstName(),
                'l_name' => $user->getLastName(),
                'url'    => 'https://wamow.co/' . $this->get('router')->generate('fos_user_resetting_reset', [
                        'token' => $user->getConfirmationToken(),
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ])
            ]);

            $user->setPasswordRequestedAt(new \DateTime());
            $this->get('fos_user.user_manager')->updateUser($user);

            /* Dispatch completed event */
            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_COMPLETED, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }
        }

        return new RedirectResponse($this->generateUrl('fos_user_resetting_check_email', ['username' => $username]));
    }

    /**
     * Reset user password.
     *
     * @param Request $request
     * @param string  $token
     *
     * @return Response
     */
    public function resetAction(Request $request, $token)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.resetting.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url      = $this->generateUrl('fos_user_profile_show');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(
                FOSUserEvents::RESETTING_RESET_COMPLETED,
                new FilterUserResponseEvent($user, $request, $response)
            );

            return $response;
        }

        return $this->render('@FOSUser/Resetting/reset.html.twig', [
            'token' => $token,
            'form'  => $form->createView(),
        ]);
    }


}
