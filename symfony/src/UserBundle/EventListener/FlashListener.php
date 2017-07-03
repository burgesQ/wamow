<?php

namespace UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;
use FOS\UserBundle\EventListener\FlashListener as BaseListener;


class FlashListener extends BaseListener
{

    /**
     * FlashListener constructor.
     *
     * @param Session             $session
     * @param TranslatorInterface $translator
     */
    public function __construct(Session $session, TranslatorInterface $translator)
    {
        parent::__construct($session, $translator);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::CHANGE_PASSWORD_COMPLETED => 'addSuccessFlash',
            FOSUserEvents::GROUP_CREATE_COMPLETED => 'addSuccessFlash',
            FOSUserEvents::GROUP_DELETE_COMPLETED => 'addSuccessFlash',
            FOSUserEvents::GROUP_EDIT_COMPLETED => 'addSuccessFlash',
            FOSUserEvents::PROFILE_EDIT_COMPLETED => 'addSuccessFlash',
            FOSUserEvents::RESETTING_RESET_COMPLETED => 'addSuccessFlash',
        );
    }
}
