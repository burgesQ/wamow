<?php

namespace MissionBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * StepRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StepRepository extends EntityRepository
{
    public function getStepsAvailables($missionId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('s')
        ->from('MissionBundle:Step', 's')
        ->where('s.status >= 0')
        ->andWhere('s.mission = :missionId')
            ->setParameter('missionId', $missionId);

        return $qb->getQuery()->getResult();
    }
}
