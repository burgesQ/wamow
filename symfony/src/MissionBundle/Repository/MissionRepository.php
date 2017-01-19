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
    public function getExpertMissionsAvailables()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('m')
        ->from('MissionBundle:Mission', 'm')
        ->where('m.status >= 1')
        ->orderBy('m.applicationEnding', 'DESC');
        return $qb->getQuery()->getResult();
    }

    public function getSeekerMissions($userId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('m')
            ->from('MissionBundle:Mission', 'm')
            ->leftjoin('m.teamContact', 't')
            ->leftjoin('t.users', 'u')
            ->where('u.id = :userId')
                ->setParameter('userId', $userId)
            ->andWhere('m.status >= 0')
            ->orderBy('m.applicationEnding', 'DESC');
        return $qb->getQuery()->getResult();
    }
}
