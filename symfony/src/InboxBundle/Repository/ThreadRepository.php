<?php

// src/InboxBundle/Repository/ThreadRepository.php

namespace InboxBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ThreadRepository
 */
class ThreadRepository extends EntityRepository
{

    public function getThreadByMission($missionId)
    {
        $qb = $this->_em->createQueryBuilder();

         $qb->select('t')
             ->from('InboxBundle:Thread', 't')
             ->leftjoin('t.mission', 'm')
             ->where('m.id = :missionId')
             ->setParameter('missionId', $missionId)
             ;
        return $qb->getQuery()->getResult();
    }

    public function getThreadsByUser($userID)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('t', 'p', 'm')
            ->from('InboxBundle:Thread', 't')
            ->leftjoin('t.metadata', 'm')
            ->leftjoin('m.participant', 'p')
            ->where('p.id = :userID')
            ->setParameter('userID', $userID)
            ;

        return $qb->getQuery()->getResult();
    }
    
}
