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
            if ($event->getOldValue("status") == UserMission::SCORED
                && $event->getNewValue("status") == UserMission::MATCHED) {
                $this->sendMailAdvisor($event, 'mails.subject.new_mission', 'mails.content.new_contractor');
            } elseif ($event->getOldValue("status") == UserMission::MATCHED
                && $event->getNewValue("status") == UserMission::SHORTLIST) {
                $this->sendMailAdvisor($event, 'mails.subject.user_shortlisted', 'mails.content.user_shortlisted');
            } elseif ($event->getOldValue("status") == UserMission::MATCHED
                && $event->getNewValue("status") == UserMission::DISMISS) {
                $this->sendMailAdvisor($event, 'mails.subject.response', 'mails.content.no_shortlisted');
            } elseif ($event->getOldValue("status") == UserMission::SHORTLIST
                && $event->getNewValue("status") == UserMission::DISMISS) {
                $this->sendMailAdvisor($event, 'mails.subject.response', 'mails.content.no_finalist');
            }

        }
    }

    /**
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $event
     * @param string                                 $title
     * @param string                                 $content
     */
    private function sendMailAdvisor(PreUpdateEventArgs $event, string $title, string $content)
    {
        /** @var UserMission $userMission */
        $userMission = $event->getEntity();
        $advisor     = $userMission->getUser();
        if ($advisor->getNotification()) {
            $message = Swift_Message::newInstance()
                ->setSubject($this->trans->trans($title, [], 'tools'))
                ->setFrom($this->sender)
                ->setTo($advisor->getEmail())
                ->setBody($this->trans->trans($content, [
                    'fName'        => $advisor->getFirstName(),
                    'lName'        => $advisor->getLastName(),
                    'missionTitle' => $userMission->getMission()->getTitle()
                ], 'tools'), 'text');
            $this->mailer->send($message);
        }
    }
}
