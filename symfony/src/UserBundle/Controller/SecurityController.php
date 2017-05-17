<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController
{
    public function loginAction(Request $request)
    {
        return $this->render('@User/Security/login.html.twig', [
            'last_username' => null,
            'error'         => null,
            'csrf_token'    => $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
        ]);
    }
}
