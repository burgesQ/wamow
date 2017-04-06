<?php

namespace BlogBundle\Controller;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use BlogBundle\Entity\NewsLetter;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\Comment;

class LoadNewsLetters extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $fileName = 'NewsLetters.json';
        $finder   = new Finder();

        // get all file in current dir
        $finder->files()->in(__DIR__);

        // get content from the json NewsLetters file
        foreach ($finder as $file)
            if (!strcmp($file->getRelativePathname(), $fileName)) {
                $string = file_get_contents($file->getRealPath());
            }

        // if nothing was found exit
        if (empty($string) || !($json = json_decode($string))) {
            exit;
        }

        // foreach newsLetter
        foreach ($json as $oneNews) {
            $this->createNewsLetterFromJson($manager, $oneNews);
        }

        // save the relation between newsLetter and article
        $manager->flush();
    }

    /**
     * Create a NewsLetter from a Json
     *
     * @param ObjectManager $manager
     * @param               $oneNews
     */
    private function createNewsLetterFromJson($manager, $oneNews)
    {
        // init the number for the newsLetter
        static $i = 1;

        // create a newsLetter from json
        $newNews = new NewsLetter(
            $i,
            $oneNews->preTitle,
            $oneNews->title,
            new \DateTime($oneNews->postDate),
            $oneNews->urlCover
        );

        // save the newsLetter and give her a id
        $manager->persist($newNews);
        $manager->flush();

        // foreach article linked to the newsletter
        foreach ($oneNews->articles as $oneArticle) {
            $this->createArticleFromJson($manager, $oneArticle, $newNews);
        }

        // increase the id for next newsLetter
        $i += 1;
    }

    /**
     * Create a article entity from Json
     * Link the article with the newsLetter
     *
     * @param ObjectManager $manager
     * @param               $oneArticle
     * @param NewsLetter    $newNews
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
        $newArticle->setNewsLetter($newNews);

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
        return 11;
    }
}
