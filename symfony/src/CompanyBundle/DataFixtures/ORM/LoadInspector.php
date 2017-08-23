<?php

namespace CompanyBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CompanyBundle\Entity\Inspector;

class LoadInspector extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var array
     */
    private $arrayName = [
        "PWC",
        "PriceWaterhouseCoopers",
        "Deloitte",
        "E&Y",
        "Ernst & Young",
        "KPMG",
        "BDO",
        "Grant Thornton",
        "RSM",
        "Baker Tilly",
        "Crowe Horwath",
        "Nexia",
        "PKF",
        "Moore Stephens",
        "Kreston",
        "HLB",
        "Mazars",
        "UHY",
        "Russel Bedford",
        "ECOVIS",
        "IECnet",
        "Reanda",
        "SMS Latinoamérica",
        "UC&CS America"
    ];

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->arrayName as $oneName) {
            $inspector = new Inspector($oneName);
            $manager->persist($inspector);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 11;
    }
}
