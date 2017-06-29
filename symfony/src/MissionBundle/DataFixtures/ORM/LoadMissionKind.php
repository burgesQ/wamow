<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\MissionKind;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadMissionKind extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $names = [
            'typemissions.strategic',

            'typemissions.csp',
            'typemissions.moa',
            'typemissions.moe',
            'typemissions.tma',

            'typemissions.infrastructure',
            'typemissions.migration',
            'typemissions.implementation',

            'typemissions.execution',
            'typemissions.organisationtransformation',
            'typemissions.change',
            'typemissions.businessreingeniering',

            'typemissions.interimmanagement',
            'typemissions.recruitment',
            'typemissions.coaching',
            'typemissions.learning',
            'typemissions.audit',
            'typemissions.costkilling',
            'typemissions.outplacement',
            'typemissions.certification',
            'typemissions.outsourcing',
            'typemissions.mediation',
            'typemissions.communication',
            'typemissions.knowledgemanagement',
            'typemissions.crm',
        ];

        foreach ($names as $name) {
            $kind = new MissionKind();
            $kind->setName($name);
            $manager->persist($kind);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 6;
    }
}
