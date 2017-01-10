<?php

namespace MissionBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MissionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MissionRepository extends EntityRepository
{
    public function expertMissionsAvailables()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('m')
        ->from('MissionBundle:Mission', 'm')
        ->where('m.status >= 1')
        ->orderBy('m.applicationEnding', 'DESC');
        return $qb->getQuery()->getResult();
    }

    public function getSpecificStep($missionId, $stepPos)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('s', 'm')
        ->from('MissionBundle:Step', 's')
        ->leftjoin('s.mission', 'm')
        ->where('m.id = :missionId')
            ->setParameter('missionId', $missionId)
        ->andWhere('s.position = :stepPos')
            ->setParameter('stepPos', $stepPos);
        return $qb->getQuery()->getResult();
    }

    public function getCurrentStep($missionId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('s', 'm')
        ->from('MissionBundle:Step', 's')
        ->leftjoin('s.mission', 'm')
        ->where('m.id = :missionId')
            ->setParameter('missionId', $missionId)
        ->andWhere('s.status = 1');
        return $qb->getQuery()->getResult();
    }
}
