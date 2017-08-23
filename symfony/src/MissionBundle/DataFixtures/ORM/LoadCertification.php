<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\Certification;

class LoadCertification extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var array
     */
    private $arrayName = [
            'C++',
            'C',
            'Java',
            'PHP',
            'GO',
            'SQL',
            'Python',
            'C#',
            'R',
            'JavaScript',
            'Ruby',
            'Matlab',
            'Objective C',
            'Swift',
            'Shell',
            'Perl',
            'HTML',
            'Scala',
            'Delphi',
            'Scheme',
            'Actionscript',
            'Ruby on Rail',
            'Assembly',
            'Arduino',
            'D',
            'Haskell',
            'VHDL',
            'Ada',
            'LabView',
            'Erlang',
            'OcamL',
            'ITIL',
            'CMMI',
            'COBIT',
            'eSCM',
            'Black Belt',
            'Green Belt'
        ];

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->arrayName as $oneName) {
            $inspector = new Certification($oneName);
            $manager->persist($inspector);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 12;
    }
}
