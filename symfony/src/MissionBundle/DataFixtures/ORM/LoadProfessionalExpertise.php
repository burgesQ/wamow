<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\ProfessionalExpertise;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadProfessionalExpertise extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $names = [
            'professionalexpertises.businessunitmanager',
            'professionalexpertises.humanresources',
            'professionalexpertises.purchasing',
            'professionalexpertises.production',
            'professionalexpertises.salesbusinessdevelopment',
            'professionalexpertises.marketinginnovation',
            'professionalexpertises.communication',
            'professionalexpertises.financeaccounting',
            'professionalexpertises.controlling',
            'professionalexpertises.it',
            'professionalexpertises.customerservicesupport',
            'professionalexpertises.distribution',
            'professionalexpertises.researchdevelopment',
            'professionalexpertises.administrativemanagement',
            'professionalexpertises.operations',
            'professionalexpertises.riskmanagement',
            'professionalexpertises.qualitycontrol',
            'professionalexpertises.assetmanagement',
            'professionalexpertises.supplychain',
            'professionalexpertises.digital',
            'professionalexpertises.law',
            'professionalexpertises.csr',
        ];

        foreach ($names as $name) {
            $expertise = new ProfessionalExpertise();
            $expertise->setName($name);
            $manager->persist($expertise);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 4;
    }
}
