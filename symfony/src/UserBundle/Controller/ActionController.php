<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ActionController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function switchNotificationAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('Not a xml request');
        }

        // get user
        if (!($user = $this->getUser())) {
            throw new NotFoundHttpException();
        }

        $user->setNotification(!$user->getNotification());
        // switch user notification
        $this->getDoctrine()->getManager()->flush();

        return new Response('Oukey');
   }
}