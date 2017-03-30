<?php

namespace BlogBundle\Controller;

use Doctrine\Common\{
    Persistence\ObjectManager,
    DataFixtures\OrderedFixtureInterface,
    DataFixtures\AbstractFixture
};

use BlogBundle\Entity\{
    NewsLetter
};

class LoadNewsLetters extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $newsOne = new NewsLetter(
            1,
            "One",
            "<h1>Premier titre mamen</h1>",
            new \DateTime()
        );

        $newsTwo = new NewsLetter(
            2,
            "Two",
            "<h2>Second titre askip</h2>",
            new \DateTime()
        );

        $newsOne->setCreationDate(new \DateTime());
        $newsTwo->setCreationDate(new \DateTime());

        $manager->persist($newsOne);
        $manager->persist($newsTwo);
        $manager->flush();

    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 11;
    }
}
