<?php

namespace NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use NotificationBundle\Entity\Notification;

class NotificationController extends Controller
{
    function listAction()
    {
        //TODO mettre notif a seen == true
        $trans = $this->get('translator');
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            return $this->redirectToRoute('home_page_advisor');

        $user = $this->getUser();

        if ( $user === null ) {
            throw new NotFoundHttpException($trans->trans('notification.error.logged', array(), 'NotificationBundle'));
        }
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('NotificationBundle:Notification');

        $user = $this->getUser();
        $notifications = $repository->getMyNotifications($user->getId(), 10, 0);
        return $this->render('NotificationBundle:Default:index.html.twig', array(
            'notifications'           => $notifications,
            'user'                    => $user,
        ));
    }
}
