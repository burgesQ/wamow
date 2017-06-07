<?php
namespace MissionBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;

use MissionBundle\Entity\Mission;

class MissionListener
{
    private $scoringService;

    public function __construct($scoringService)
    {
        $this->scoringService = $scoringService;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        if ($entity instanceof Mission) {
            $this->scoringService->updateUserMissions($entity);
            $entityManager->flush();
            $this->scoringService->updateScorings($entity);
            $entityManager->flush();
            $this->scoringService->updateActivated($entity);
            $entityManager->flush();
            return;
        }
    }
}
