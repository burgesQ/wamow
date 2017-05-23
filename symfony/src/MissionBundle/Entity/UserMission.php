<?php

namespace MissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserMission
 * @ORM\Table(name="user_mission")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\UserMissionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserMission
{
    // USER_MISSION STATUS
    const DELETED    = -6;
    const ENDDATE    = -5;
    const DISMISS    = -4;
    const GIVEUP     = -3;
    const REFUSED    = -2;
    const NEW        = -1;
    const INTERESTED = 0;
    const ONGOING    = 1;
    const SHORTLIST  = 2;
    const FINALIST   = 3;

    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var \DateTime
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="update_date", type="datetime", nullable=false)
     */
    private $updateDate;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="UserBundle\Entity\User",
     *     inversedBy="userMission"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="MissionBundle\Entity\Mission",
     *     inversedBy="userMission"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $mission;

    /**
     * @ORM\OneToOne(
     *     targetEntity="InboxBundle\Entity\Thread",
     *     mappedBy="userMission"
     * )
     * @ORM\JoinColumn(nullable=true)
     */
    private $thread;

    /**
     * @var int
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var \DateTime
     * @ORM\Column(name="interested_at", type="datetime", nullable=true)
     */
    private $interestedAt;

    /**
     * @var string
     * @ORM\Column(name="note", type="text", nullable=false)
     */
    private $note;

    /**
     * UserMission constructor.
     *
     * @param $user
     * @param $mission
     */
    public function __construct($user, $mission)
    {
        $this->status       = self::NEW;
        $this->creationDate = new \DateTime();
        $this->updateDate   = new \DateTime();
        $this->user         = $user;
        $this->mission      = $mission;
        $this->note         = "";
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
     * @return UserMission
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get status
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return UserMission
     */
    public function setStatus($status)
    {
        $this->status = $status;

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
     * @return UserMission
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get user
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return UserMission
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get mission
     * @return \MissionBundle\Entity\Mission
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Set mission
     *
     * @param \MissionBundle\Entity\Mission $mission
     *
     * @return UserMission
     */
    public function setMission($mission)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get thread
     * @return \InboxBundle\Entity\Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set thread
     *
     * @param \InboxBundle\Entity\Thread $thread
     *
     * @return UserMission
     */
    public function setThread($thread)
    {
        $this->thread = $thread;

        return $this;
    }


    /**
     * Set score
     *
     * @param integer $score
     * @return UserMission
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

     /**
     * Set interestedAt
     *
     * @param \DateTime $interestedAt
     * @return UserMission
     */
    public function setInterestedAt($interestedAt)
    {
        $this->interestedAt = $interestedAt;

        return $this;
    }

    /**
     * Get interestedAt
     *
     * @return \DateTime
     */
    public function getInterestedAt()
    {
        return $this->interestedAt;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return UserMission
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }
}
