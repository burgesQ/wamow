<?php

namespace BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use BlogBundle\Entity\Newsletter;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\Comment;

class LoadNewsletters extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $fileName = 'NewslettersV2.json';
        $finder   = new Finder();

        // get all file in current dir
        $finder->files()->in(__DIR__);

        // get content from the json Newsletters file
        foreach ($finder as $file)
            if (!strcmp($file->getRelativePathname(), $fileName)) {
                $string = file_get_contents($file->getRealPath());
            }

        // if nothing was found exit
        if (empty($string) || !($json = json_decode($string))) {
            return ;
        }

        // foreach newsletter
        foreach ($json as $oneNews) {
            $this->createNewsletterFromJson($manager, $oneNews);
        }

        // save the relation between newsletter and article
        $manager->flush();
    }

    /**
     * Create a Newsletter from a Json
     *
     * @param ObjectManager $manager
     * @param               $oneNews
     */
    private function createNewsletterFromJson($manager, $oneNews)
    {
        // create a newsletter from json
        $newNews = new Newsletter(
            $oneNews->number,
            $oneNews->preTitle,
            $oneNews->title,
            new \DateTime($oneNews->postDate),
            $oneNews->urlCover
        );

        // save the newsletter and give her a id
        $manager->persist($newNews);
        $manager->flush();

        // foreach article linked to the newsletter
        foreach ($oneNews->articles as $oneArticle) {
            $this->createArticleFromJson($manager, $oneArticle, $newNews);
        }
    }

    /**
     * Create a article entity from Json
     * Link the article with the newsletter
     *
     * @param ObjectManager $manager
     * @param               $oneArticle
     * @param Newsletter    $newNews
     */
    private function createArticleFromJson($manager, $oneArticle, $newNews)
    {
        // create a article from json
        $newArticle = new Article(
            $oneArticle->url,
            $oneArticle->preTitle,
            $oneArticle->title,
            $oneArticle->content,
            $oneArticle->poster,
            $oneArticle->writer,
            $oneArticle->category,
            $oneArticle->introduction,
            new \DateTime($oneArticle->creationDate),
            $oneArticle->time,
            $oneArticle->urlCover,
            $newNews
        );

        $newArticle->setUrlCover($oneArticle->urlCover);

        // save the article and give him a id
        $manager->persist($newArticle);
        $manager->flush();

        // link news and articles
        $newNews->addArticle($newArticle);
        $newArticle->setNewsletter($newNews);

        foreach ($oneArticle->comments as $oneComment)
            $this->createCommentFromJson($manager, $newArticle, $oneComment);
    }

    /**
     * @param ObjectManager $manager
     * @param Article       $newArticle
     * @param               $oneComment
     */
    private function createCommentFromJson($manager, $newArticle, $oneComment)
    {

        $newComment = new Comment(
            $oneComment->email,
            $oneComment->firstName,
            $oneComment->lastName,
            $oneComment->content,
            new \DateTime($oneComment->date)
        );
        $newComment->setStatus(1);

        $manager->persist($newComment);
        $manager->flush();

        $newArticle->addComment($newComment);
        $newComment->setArticle($newArticle);
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 18;
    }
}
