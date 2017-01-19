<?php

// src/InboxBundle/Repository/MessageRepository.php

namespace InboxBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MessageRepository
 */
class MessageRepository extends EntityRepository
{

    public function getMessageForThread($thread, $maxMessages)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('m')
            ->from('InboxBundle:Message', 'm')
            ->leftjoin('m.thread', 't')
            ->where('t = :thread')
            ->setParameter('thread', $thread)
            ->orderBy('m.id', 'DESC')
            ->setMaxResults($maxMessages)
            ;

        $array = $qb->getQuery()->getResult();
        $array = array_reverse($array);

        return $array;
    }
    
}
