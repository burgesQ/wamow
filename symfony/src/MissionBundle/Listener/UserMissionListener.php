<?php

namespace MissionBundle\Listener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use MissionBundle\Entity\UserMission;
use Swift_Message;

/**
 * Class UserMissionListener
 *
 * @package MissionBundle\Listener
 */
class UserMissionListener
{
    /**
     * @var \Swift_Mailer $mailer
     */
    protected $mailer;

    /**
     * @var \Symfony\Component\Translation\Translator $trans
     */
    protected $trans;

    /**
     * @var string $sender
     */
    protected $sender;

    /**
     * UserMissionListener constructor.
     *
     * @param \Swift_Mailer                             $mailer
     * @param \Symfony\Component\Translation\Translator $translator
     * @param string                                    $sender
     */
    public function __construct($mailer, $translator, $sender)
    {
        $this->mailer = $mailer;
        $this->trans  = $translator;
        $this->sender = $sender;
    }

    /**
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $event
     */
    public function preUpdate(PreUpdateEventArgs $event)
    {
        if ($event->hasChangedField("status")) {
            if ($event->getOldValue("status") == UserMission::ACTIVATED
                && $event->getNewValue("status") == UserMission::MATCHED) {
                /** @var UserMission $userMission */
                $userMission = $event->getEntity();
                $advisor     = $userMission->getUser();
                if ($advisor->getNotification()) {
                    $message = Swift_Message::newInstance()
                        ->setSubject($this->trans->trans('mails.subject.new_mission', [], 'tools'))
                        ->setFrom($this->sender)
                        ->setTo($advisor->getEmail())
                        ->setBody($this->trans->trans('mails.content.new_contractor', [
                            'fName'        => $advisor->getFirstName(),
                            'lName'        => $advisor->getLastName(),
                            'missionTitle' => $userMission->getMission()->getTitle()
                        ], 'tools'), 'text');
                    $this->mailer->send($message);
                }
            }
        }
    }
}
