<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Controller\ResettingController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

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

        $userRepo = $this->getDoctrine()->getRepository('UserBundle:User');
        $user     = $userRepo->findOneBy(['username' => $userName]);

        return $this->render('UserBundle:Resetting:check_email.html.twig', [
            'email' => $user->getEmail()
        ]);
    }
}
