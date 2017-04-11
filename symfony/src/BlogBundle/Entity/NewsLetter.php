<?php

namespace BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * Newsletter
 * @ORM\Table(name="newsletter")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\NewsletterRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Newsletter
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pre_title", type="string", length=255, nullable=false)
     */
    private $preTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=false)
     */
    private $updateDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_date", type="datetime", nullable=false)
     */
    private $publishedDate;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer", nullable=false)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(
     *     name="url_cover",
     *     type="string",
     *     length=255,
     *     nullable=false
     * )
     */
    private $urlCover;

    /**
     * @ORM\OneToMany(
     *   targetEntity="BlogBundle\Entity\Article",
     *   mappedBy="newLetter"
     * )
     * @OrderBy({"id" = "ASC"})
     * @var Article[]|Collection
     */
    private $articles;

    /**
     * Newsletter constructor.
     *
     * @param $number
     * @param $preTitle
     * @param $title
     * @param $publishedDate
     * @param $urlCover
     */
    public function __construct($number, $preTitle, $title, $publishedDate, $urlCover)
    {
        $this->preTitle      = $preTitle;
        $this->title         = $title;
        $this->creationDate  = new \DateTime();
        $this->updateDate    = new \DateTime();
        $this->publishedDate = $publishedDate;
        $this->number        = $number;
        $this->urlCover      = $urlCover;
        $this->articles      = new ArrayCollection();
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
     * @param $preTitle
     *
     * @return Newsletter
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
     * @return Newsletter
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * @return Newsletter
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
     * @return Newsletter
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

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
     * @return Newsletter
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    /**
     * Get number
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return Newsletter
     */
    public function setNumber($number)
    {
        $this->number = $number;

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
     * @return Newsletter
     */
    public function setUrlCover($urlCover)
    {
        $this->urlCover = $urlCover;

        return $this;
    }

    /**
     * Add articles
     *
     * @param \BlogBundle\Entity\Article $articles
     * @return Newsletter
     */
    public function addArticle($articles)
    {
        $this->articles[] = $articles;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \BlogBundle\Entity\Article $articles
     */
    public function removeArticle($articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }
}
