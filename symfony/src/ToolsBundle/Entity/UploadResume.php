<?php

namespace ToolsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UploadResume
 *
 * @ORM\Table(name="upload_resume")
 * @ORM\Entity(repositoryClass="ToolsBundle\Repository\UploadResumeRepository")
 */
class UploadResume extends Upload
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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User",
     *     inversedBy="resumes", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(
     *     name="content",
     *     type="string",
     *     nullable=true
     * )
     */
    private $content;

    /**
     * UploadResume constructor.
     */
    public function __construct()
    {
        parent::__construct();
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
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return Upload
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set content
     *
     * @param string $content
     * @return UploadResume
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
}
