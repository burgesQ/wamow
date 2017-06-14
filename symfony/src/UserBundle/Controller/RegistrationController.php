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
            (($url = $this->get('signed_up')->checkIfSignedUp($user->getStatus())) !==  null)) {

            return $this->redirectToRoute($url);
        }
        elseif (!$user &&
                !$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            return $this->redirectToRoute('expert_registration_step_zero');
        }

        return $this->redirectToRoute('dashboard');
    }
}
