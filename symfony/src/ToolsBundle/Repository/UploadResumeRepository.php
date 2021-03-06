<?php

namespace ToolsBundle\Repository;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Validator\Constraints\DateTime;

/**
 * UploadResumeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UploadResumeRepository extends EntityRepository
{

    public function getLastResumeByUser($user)
    {
        $resume = array();
        $resume = $this->findByUser($user);

        usort($resume, function($a, $b) {
            $ad = $a->getUploadDate();
            $bd = $b->getUploadDate();

            if ($ad == $bd)
                return 0;

            return $ad > $b ? -1 : 1;
        });

        return (empty($resume)) ? null : $resume[0];
    }
}
