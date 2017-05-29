<?php
namespace MissionBundle\Listener;

use Doctrine\ORM\Event\PreUpdateEventArgs;

use MissionBundle\Entity\UserMission;

class UserMissionListener
{
    public function preUpdate(UserMission $userMission, PreUpdateEventArgs $event)
    {
        if ($event->hasChangedField("status")) {
            if ($event->getOldValue("status") == UserMission::MATCHED && $event->getNewValue("status") == UserMission::INTERESTED) {
                // TODO : Send mail, and ?
            }
        }
    }
}
