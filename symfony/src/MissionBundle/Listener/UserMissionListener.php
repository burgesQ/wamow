<?php

namespace MissionBundle\Listener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use MissionBundle\Entity\UserMission;

/**
 * Class UserMissionListener
 *
 * @package MissionBundle\Listener
 */
class UserMissionListener
{
    /**
     * @var \ToolsBundle\Service\WamowMailerService
     */
    protected $wamowMailer;

    protected $trans;

    /**
     * UserMissionListener constructor.
     *
     * @param \ToolsBundle\Service\WamowMailerService   $wamowMailer
     * @param \Symfony\Component\Translation\Translator $translator
     */
    public function __construct($wamowMailer, $translator)
    {
        $this->wamowMailer = $wamowMailer;
        $this->trans       = $translator;
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
                $this->sendMailAdvisor($event, 'mails.subject.response', 'mails.content.no_shortlist');
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

            $this->wamowMailer->sendWamowMails(
                $title,
                $advisor->getEmail(),
                'Emails/classic.html.twig', [
                'content' => $this->trans->trans($content, [
                    'fName'        => $advisor->getFirstName(),
                    'lName'        => $advisor->getLastName(),
                    'missionTitle' => $this->trans->trans($userMission->getMission()->getTitle(), [], 'tools')
                ], 'tools')
            ]);

        }
    }
}
