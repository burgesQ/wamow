<?php

namespace MissionBundle\Repository;

use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityRepository;

/**
 * CertificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CertificationRepository extends EntityRepository
{
    public function findAutocompleteCertification($q, $page, $pageLimit)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c')
            ->from('MissionBundle:Certification', 'c')
            ->where('c.name LIKE :q')
            ->setParameter('q', $q . '%')
        ;

        if (!is_numeric($page)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $page est incorrecte (valeur : ' . $page . ' ).');
        } elseif (!is_numeric($pageLimit)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $pageLimit est incorrecte (valeur : ' . $pageLimit . ' ).');
        } elseif ($page < 1 || $pageLimit < 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas');
        }
        $firstResult = ($page - 1) * $pageLimit;
        $query       = $qb->getQuery();
        $query->setFirstResult($firstResult)->setMaxResults($pageLimit);

        return new Paginator($query);
    }
}