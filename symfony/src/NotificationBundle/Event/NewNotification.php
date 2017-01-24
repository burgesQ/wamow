<?php

namespace NotificationBundle\Event;

use NotificationBundle\Entity\Notification;

class NewNotification
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function new($user, $status, $message, $param)
    {
        $em = $this->em;
        $notification = new Notification($user, $status, $message, $param);
        $em->persist($notification);
        $em->flush();
    }
}
