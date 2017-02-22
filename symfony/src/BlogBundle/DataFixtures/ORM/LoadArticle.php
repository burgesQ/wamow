<?php

namespace BlogBundle\DataFixtures\ORM;

use Doctrine\Common\{
    Persistence\ObjectManager,
    DataFixtures\OrderedFixtureInterface,
    DataFixtures\AbstractFixture
};

use BlogBundle\Entity\{
    Article,
    Comment
};

class LoadArticle extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $userName = $manager->getRepository('UserBundle:User')
            ->findOneBy(array('id' => 1))->getEmail();

        $article = new Article(
            "uno",
            "Premier titre mamen",
            "V'la l'content aussi laaaaaaaaaaa",
            $userName,
            $userName
        );

        $comment = new Comment(
            $userName,
            "Pulitzer price of the year ! Give this man a Oscar !!"
        );

        $manager->persist($article);
        $manager->flush();

        $comment->setArticle($article);
        $comment->setStatus(1);
        $manager->persist($comment);
        $manager->flush();

        $article->addComment($comment);
        $manager->persist($article);
        $manager->flush();

    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 15;
    }
}
