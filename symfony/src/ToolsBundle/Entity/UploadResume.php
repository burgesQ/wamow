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
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User",
     *     inversedBy="resumes", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\UserMission",
     *     inversedBy="proposals", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $userMission;

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

        $this
            ->addFormat('doc')
            ->addFormat('vnd.openxmlformats-officedocument.wordprocessingml.document') // docx
            ->addFormat('pdf')
        ;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return Upload
     */
    public function setUser($user = null)
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

    /**
     * Set userMission
     *
     * @param \MissionBundle\Entity\UserMission $userMission
     * @return UploadResume
     */
    public function setUserMission($userMission = null)
    {
        $this->userMission = $userMission;

        return $this;
    }

    /**
     * Get userMission
     *
     * @return \MissionBundle\Entity\UserMission 
     */
    public function getUserMission()
    {
        return $this->userMission;
    }
}
