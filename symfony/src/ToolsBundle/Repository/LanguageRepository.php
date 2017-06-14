<?php

namespace ToolsBundle\Repository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;

/**
 * LanguageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LanguageRepository extends EntityRepository
{
    /**
     * @param $q
     * @param $page
     * @param $pageLimit
     * @return \ToolsBundle\Repository\Paginator
     */
    public function findAutocompleteCertification($q, $page, $pageLimit)
    {

        $qb = $this->_em->createQueryBuilder();
        $qb->select('l')
            ->from('ToolsBundle:Language', 'l')
            ->where('l.name LIKE :q')
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
