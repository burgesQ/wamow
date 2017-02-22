<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\{
    Context\ExecutionContextInterface,
    Constraints as Assert
};

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\CommentRepository")
 */
class Comment
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
     * @var \DateTime
     *
     * @ORM\Column(name="post_date", type="datetime", nullable=false)
     */
    private $postDate;

    /**
     * @var \BlogBundle\Entity\Article
     *
     * @ORM\ManyToOne(
     *   targetEntity="BlogBundle\Entity\Article",
     *   inversedBy="comments"
     * )
     */
    protected $article;

    /**
     * @var string
     *
     * @ORM\Column(name="email_author", type="string", length=255, nullable=false)
     * @Assert\Email()
     */
    private $emailAuthor;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    public function __construct($emailAuthor, $content)
    {
        $this->postDate = new \DateTime();
        $this->article = null;
        $this->emailAuthor = $emailAuthor;
        $this->content = $content;
        $this->status = 0;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setPostDate(new \Datetime());
    }

    /**
     * @Assert\Callback
     */
    public function isValidate(ExecutionContextInterface $context)
    {
        // check content
    }

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
     * Set postDate
     *
     * @param \DateTime $postDate
     * @return Comment
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
     * Set article
     *
     * @param \BlogBundle\Entity\Article $article
     * @return Comment
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \BlogBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set emailAuthor
     *
     * @param string $emailAuthor
     * @return Comment
     */
    public function setEmailAuthor($emailAuthor)
    {
        $this->emailAuthor = $emailAuthor;

        return $this;
    }

    /**
     * Get emailAuthor
     *
     * @return string 
     */
    public function getEmailAuthor()
    {
        return $this->emailAuthor;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Comment
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
     * Set status
     *
     * @param integer $status
     * @return Comment
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

}
