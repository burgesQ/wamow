<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\{
    Context\ExecutionContextInterface,
    Constraints as Assert
};

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Article
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
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", length=255, nullable=false)
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime", length=255, nullable=false)
     */
    private $updateDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="post_date", type="datetime", length=255, nullable=false)
     */
    private $postDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_date", type="datetime", length=255, nullable=false)
     */
    private $publishedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="posted_by", type="string", length=255, nullable=false)
     * @Assert\Email()
     */
    private $postedBy;

    /**
     * @var string
     *
     * @ORM\Column(name="writed_by", type="string", length=255, nullable=false)
     * @Assert\Email()
     */
    private $writedBy;

    /**
     * @ORM\OneToMany(
     *   targetEntity="BlogBundle\Entity\Comment",
     *   mappedBy="articles"
     * )
     * @var Comment[]|Collection
     */
    protected $comments;

    public function __construct($url, $title, $content, $poster, $writer)
    {
        $this->url = $url;
        $this->title = $title;
        $this->content = $content;

        $this->creationDate = new \DateTime();
        $this->updateDate = new \DateTime();
        $this->postDate = new \DateTime();
        $this->publishedDate = new \DateTime();

        $this->postedBy = $poster;
        $this->writedBy = $writer;

        $this->comments = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdateDate(new \Datetime());
    }

    /**
     * @Assert\Callback
     */
    public function isValidate(ExecutionContextInterface $context)
    {}

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Article
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Article
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return Article
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set postDate
     *
     * @param \DateTime $postDate
     * @return Article
     */
    public function setPostDate($postDate)
    {
        $this->postDate = $postDate;

        return $this;
    }

    /**
     * Get postDate
     *
     * @return \DateTime
     */
    public function getPostDate()
    {
        return $this->postDate;
    }

    /**
     * Set publishedDate
     *
     * @param \DateTime $publishedDate
     * @return Article
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    /**
     * Get publishedDate
     *
     * @return \DateTime
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * Set postedBy
     *
     * @param string $postedBy
     * @return Article
     */
    public function setPostedBy($postedBy)
    {
        $this->postedBy = $postedBy;

        return $this;
    }

    /**
     * Get postedBy
     *
     * @return string
     */
    public function getPostedBy()
    {
        return $this->postedBy;
    }

    /**
     * Set writedBy
     *
     * @param string $writedBy
     * @return Article
     */
    public function setWritedBy($writedBy)
    {
        $this->writedBy = $writedBy;

        return $this;
    }

    /**
     * Get writedBy
     *
     * @return string
     */
    public function getWritedBy()
    {
        return $this->writedBy;
    }

    /**
     * Add comments
     *
     * @param \BlogBundle\Entity\Comment $comments
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
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

}
