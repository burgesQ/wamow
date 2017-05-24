<?php

namespace MissionBundle\Repository;

use MissionBundle\Entity\UserMission;
use Doctrine\ORM\EntityRepository;
use MissionBundle\Entity\Mission;
use UserBundle\Entity\User;

/**
 * MissionRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MissionRepository extends EntityRepository
{
    public function getMissionsToScore()
    {
        // TODO : Exclude already SHORTLIST / FINALIST / etc missions
        $qb = $this->_em->createQueryBuilder();
        $qb->select('m')
        ->from('MissionBundle:Mission', 'm')
        ->where('m.status = '.Mission::PUBLISHED)
        ->andWhere('m.nextUpdateScoring <= :currentDate')
        ->setParameter("currentDate", date('Y-m-d'))
        ;
        return $qb->getQuery()->getResult();
    }

    public function getContractorMissions($userId, $companyId, $status)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('m')
        ->from('MissionBundle:Mission', 'm')
        ->where('m.contact = :userId')
            ->setParameter('userId', $userId)
        ->andWhere('m.company = :companyId')
            ->setParameter('companyId', $companyId)
        ->andWhere('m.status > :status')
            ->setParameter('status', $status);
        return $qb->getQuery()->getResult();
    }

    /**
     * A magic query that return a array of potential mission for a user
     * Need to refacto that shit, that burn my eyes
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Mission[]
     */
    public function getMissionByUser($user)
    {
        // select all mission at step one
        // left join necessary table
        $base = "SELECT   m
                FROM      MissionBundle:Mission m
                LEFT JOIN m.languages l
                LEFT JOIN m.businessPractice b
                LEFT JOIN m.professionalExpertise p
                LEFT JOIN m.missionKinds k
                LEFT JOIN m.userMission um
                WHERE     um.user = :user
                AND       um.status >= " . UserMission::ACTIVATED ."
                AND       m.status = " . Mission::PUBLISHED;

        // add each user language in the query
        $i = 0;
        foreach ($user->getLanguages() as $oneLanguage) {
            if ($i)
                $base = $base . '
                OR        l = :userLanguage' . $i;
            else
                $base = $base . '
                AND       (l = :userLanguage' . $i;
            $i++;
        }
        // add each user practice in the query
        $i = 0;
        foreach ($user->getBusinessPractice() as $onePractice) {
            if ($i)
                $base = $base . '
                OR        b = :userPractice' . $i;
            else
                $base = $base . ')
                AND       (b = :userPractice' . $i;
            $i++;
        }
        // add each user expertise in the query
        $i = 0;
        foreach ($user->getProfessionalExpertise() as $oneExp) {
            if ($i)
                $base = $base . '
                OR        p = :userExp' . $i;
            else
                $base = $base . ')
                AND       (p = :userExp' . $i;
            $i++;
        }
        // add each user mission kind in the query
        $i = 0;
        foreach ($user->getMissionKind() as $oneKind) {
            if ($i)
                $base = $base . '
                OR        k = :userKind' . $i;
            else
                $base = $base . ')
                AND       (k = :userKind' . $i;
            $i++;
        }

        // add ordering method to the query
        $base  = $base . ')
         ORDER BY         m.applicationEnding DESC';
        $query = $this->_em->createQuery($base);

        // add parameters to the query
        $query->setParameter('user', $user);
        $i = 0;
        foreach ($user->getLanguages() as $oneLanguage) {
            $query->setParameter('userLanguage' . $i, $oneLanguage);
            $i++;
        }
        $i = 0;
        foreach ($user->getBusinessPractice() as $onePractice) {
            $query->setParameter('userPractice' . $i, $onePractice);
            $i++;
        }
        $i = 0;
        foreach ($user->getProfessionalExpertise() as $oneExp) {
            $query->setParameter('userExp' . $i, $oneExp);
            $i++;
        }
        $i = 0;
        foreach ($user->getMissionKind() as $oneKind) {
            $query->setParameter('userKind' . $i, $oneKind);
            $i++;
        }

        // return the result + the query
        return $query->getResult();
    }

    public function findUsersByMission($mission)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('u')
            ->from('UserBundle:User', 'u')
            ->join("u.languages", "l")
            ->join("u.professionalExpertise", "pe")
            ->leftJoin("u.missionKind", "mk")
            ->leftJoin("u.businessPractice", "bp")
            ->leftJoin("u.userMission", "um")
            ->leftJoin("um.mission", "m")
            // NOTE : user status, no CONST ?!
            ->where("u.status = 5")
            ->andWhere("u.roles = :userRoles")
            ->andWhere("l.id IN(:languageIds)")
            // NOTE : only in Scoring ?
            // ->andWhere("mk.id IN(:missionKinds)")
            ->andWhere("pe = :professionalExpertise OR bp = :businessPractice")
            ->andWhere("m.telecommuting = ".($mission->getTelecommuting() ? "1" : "0"))
            // TODO : No result, normal ? Column medium price needed ?
            // ->andWhere("((u.dailyFeesMin + u.dailyFeesMax)/2.0) <= m.budget")
        ;

        $parameters = array(
            "userRoles" => "a:1:{i:0;s:12:\"ROLE_ADVISOR\";}",
            "languageIds" => $mission->getLanguages()->toArray(),
            "professionalExpertise" => $mission->getProfessionalExpertise(),
            // "missionKinds" => $mission->getMissionKinds()->toArray(),
            "businessPractice" => $mission->getBusinessPractice()
        );
        $qb->setParameters($parameters);
        $qb->groupBy("u.id");
        $qb->orderBy("u.id", "asc");

        return $qb->getQuery()->getResult();
    }
    /**
     * A magic query that return a array of potential user for a mission
     * Need to refacto that shit, that burn my eyes
     *
     * @param \MissionBundle\Entity\Mission $mission
     * @param bool                          $ignored
     * @param bool                          $dql
     *
     * @return User[]
     */
    public function getUsersByMission($mission, $ignored = true, $dql = false)
    {
        // NOTE : $ignored param ?!
        // select all advisor fully register
        // left join necessary table
        $base = "SELECT   u
                FROM      UserBundle:User u
                LEFT JOIN u.languages l
                LEFT JOIN u.professionalExpertise p
                LEFT JOIN u.missionKind k
                LEFT JOIN u.businessPractice b
                LEFT JOIN u.userMission um
                WHERE     u.status = " . User::REGISTER_NO_STEP ."
                AND       u.roles = :userRoles";

        // add language filter
        $i = 0;
        foreach ($mission->getLanguages() as $oneLanguage) {
            if ($i)
                $base = $base . '
                OR        l = :missionLanguage' . $i;
            else
                $base = $base . '
                AND       (l = :missionLanguage' . $i;
            $i++;
        }
        // add professionalExpertise filter
        $base = $base . ')
                AND       p = :professionalExpertise';
        // add missionKind filter
        $i = 0;
        foreach ($mission->getMissionKinds() as $missionKind) {
            if ($i) {
                $base = $base . '
                    OR        k = :missionKinds' . $i;
            } else {
                $base = $base . '
                    AND       (k = :missionKinds' . $i;
            }
            $i++;
        }

        // add businessPractice filter
        $base = $base . ')
                AND       b = :businessPractice';
        // if ingored, add userMission filter (user that haven't refused the mission)
        if ($ignored)
            $base = $base . '
                AND       um.mission = :mission
                AND       um.status >= ' . UserMIssion::ACTIVATED;
        // order user by id
        $base = $base . '
                GROUP BY u.id ORDER BY  u.id ASC';

        // create DQL query
        $query = $this->_em->createQuery($base);

        // add parameters to the query
        $query->setParameter('userRoles', "a:1:{i:0;s:12:\"ROLE_ADVISOR\";}");
        $i = 0;
        foreach ($mission->getLanguages() as $oneLanguage) {
            $query->setParameter('missionLanguage' . $i, $oneLanguage);
            $i++;
        }
        $i = 0;
        foreach ($mission->getMissionKinds() as $oneMissionKind) {
            $query->setParameter('missionKinds' . $i, $oneMissionKind);
            $i++;
        }
        $query->setParameter('professionalExpertise', $mission->getProfessionalExpertise());
        $query->setParameter('businessPractice', $mission->getBusinessPractice());
        if ($ignored)
            $query->setParameter('mission', $mission->getId());

        // return the result
        if ($dql)
            return [$query->getResult(), $query->getDQL()];
        return $query->getResult();
    }
}
