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
    const DELETED    = -5;
    const DISMISS    = -4;
    const GIVEUP     = -3;
    const REFUSED    = -2;
    const NEW        = -1;
    const INTERESTED = 0;
    const STEP1      = 1;
    const STEP2      = 2;
    const STEP3      = 3;

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
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="updateDate", type="datetime")
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

}
