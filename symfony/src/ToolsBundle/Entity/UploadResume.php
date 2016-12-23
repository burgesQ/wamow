<?php

namespace ToolsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use ToolsBundle\Entity\Upload;

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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="resumes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    
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

}