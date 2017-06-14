<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Article
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string
     * @ORM\Column(name="pre_title", type="string", length=255, nullable=false)
     */
    private $preTitle;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="content", type="text", nullable=false)
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var \DateTime
     * @ORM\Column(name="creation_date", type="datetime", length=255, nullable=false)
     */
    private $creationDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="update_date", type="datetime", length=255, nullable=false)
     */
    private $updateDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="post_date", type="datetime", length=255, nullable=false)
     */
    private $postDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="published_date", type="datetime", length=255, nullable=false)
     */
    private $publishedDate;

    /**
     * @var string
     * @ORM\Column(name="posted_by", type="string", length=255, nullable=false)
     * @Assert\Email()
     */
    private $postedBy;

    /**
     * @var string
     * @ORM\Column(name="written_by", type="string", length=255, nullable=false)
     * @Assert\Email()
     */
    private $writtenBy;

    /**
     * @ORM\OneToMany(
     *   targetEntity="BlogBundle\Entity\Comment",
     *   mappedBy="article"
     * )
     * @var Comment[]|Collection
     */
    private $comments;

    /**
     * @var string
     * @ORM\Column(
     *     name="category",
     *     type="string",
     *     length=255,
     *     nullable=false
     * )
     */
    private $category;

    /**
     * @var string
     * @ORM\Column(
     *     name="introduction",
     *     type="text",
     *      nullable=false
     * )
     * @Assert\NotBlank()
     */
    private $introduction;

    /**
     * @var int
     * @ORM\Column(
     *     name="time",
     *     type="integer",
     *     nullable=false
     * )
     */
    private $time;

    /**
     * @var string
     * @ORM\Column(
     *     name="url_cover",
     *     type="string",
     *     length=255,
     *     nullable=false
     * )
     */
    private $urlCover;

    /**
     * @var \BlogBundle\Entity\Newsletter
     * @ORM\ManyToOne(
     *   targetEntity="BlogBundle\Entity\Newsletter",
     *   inversedBy="articles"
     * )
     */
    private $newsletter;

    /**
     * Article constructor.
     *
     * @param                               $url
     * @param                               $preTitle
     * @param                               $title
     * @param                               $content
     * @param                               $poster
     * @param                               $writer
     * @param                               $category
     * @param                               $introduction
     * @param                               $creationDate
     * @param                               $time
     * @param                               $urlCover
     * @param \BlogBundle\Entity\Newsletter $newsletter
     */
    public function __construct($url, $preTitle, $title, $content, $poster, $writer, $category, $introduction, $creationDate, $time, $urlCover, $newsletter)
    {
        $this->publishedDate = $newsletter->getPublishedDate();
        $this->updateDate    = new \DateTime();
        $this->postDate      = new \DateTime();

        $this->url           = $url;
        $this->preTitle      = $preTitle;
        $this->title         = $title;
        $this->content       = $content;
        $this->postedBy      = $poster;
        $this->writtenBy     = $writer;
        $this->comments      = new ArrayCollection();
        $this->category      = $category;
        $this->introduction  = $introduction;
        $this->creationDate  = $creationDate;
        $this->time          = $time;
        $this->urlcover      = $urlCover;
        $this->newsletter    = $newsletter;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdateDate(new \Datetime());
    }

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get url
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Article
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get preTitle
     * @return string
     */
    public function getPreTitle()
    {
        return $this->preTitle;
    }

    /**
     * Set preTitle
     *
     * @param string $preTitle
     *
     * @return Article
     */
    public function setPreTitle($preTitle)
    {
        $this->preTitle = $preTitle;

        return $this;
    }

    /**
     * Get title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get content
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get creationDate
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Article
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get updateDate
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Article
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get postDate
     * @return \DateTime
     */
    public function getPostDate()
    {
        return $this->postDate;
    }

    /**
     * Set postDate
     *
     * @param \DateTime $postDate
     *
     * @return Article
     */
    public function setPostDate($postDate)
    {
        $this->postDate = $postDate;

        return $this;
    }

    /**
     * Get publishedDate
     * @return \DateTime
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * Set publishedDate
     *
     * @param \DateTime $publishedDate
     *
     * @return Article
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    /**
     * Get postedBy
     * @return string
     */
    public function getPostedBy()
    {
        return $this->postedBy;
    }

    /**
     * Set postedBy
     *
     * @param string $postedBy
     *
     * @return Article
     */
    public function setPostedBy($postedBy)
    {
        $this->postedBy = $postedBy;

        return $this;
    }

    /**
     * Get writtenBy
     * @return string
     */
    public function getWrittenBy()
    {
        return $this->writtenBy;
    }

    /**
     * Set writtenBy
     *
     * @param string $writtenBy
     *
     * @return Article
     */
    public function setWrittenBy($writtenBy)
    {
        $this->writtenBy = $writtenBy;

        return $this;
    }

    /**
     * Add comments
     *
     * @param \BlogBundle\Entity\Comment $comments
     *
     * @return Article
     */
    public function addComment($comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \BlogBundle\Entity\Comment $comments
     */
    public function removeComment($comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Get category
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Article
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get introduction
     * @return string
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Set introduction
     *
     * @param string $introduction
     *
     * @return Article
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     * Get time
     * @return integer
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set time
     *
     * @param integer $time
     *
     * @return Article
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get urlCover
     * @return string
     */
    public function getUrlCover()
    {
        return $this->urlCover;
    }

    /**
     * Set urlCover
     *
     * @param string $urlCover
     *
     * @return Article
     */
    public function setUrlCover($urlCover)
    {
        $this->urlCover = $urlCover;

        return $this;
    }

    /**
     * Get newsletter
     * @return \BlogBundle\Entity\Newsletter
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set newsletter
     *
     * @param \BlogBundle\Entity\Newsletter $newsletter
     *
     * @return Article
     */
    public function setNewsletter($newsletter = null)
    {
        $this->newsletter = $newsletter;

        return $this;
    }
}
