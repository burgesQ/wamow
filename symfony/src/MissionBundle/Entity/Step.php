<?php

namespace MissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Step
 *
 * @ORM\Table(name="step")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\StepRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Step
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
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\Mission",
     *      cascade={"persist"},
     *      inversedBy="steps"
     *      )
     * @ORM\JoinColumn(nullable=false)
     */
    private $mission;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="smallint")
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "You can't put something under 0.",
     * )
     */
    private $position;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_max_user", type="smallint")
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "You can't put something under 1.",
     * )
     */
    private $nbMaxUser;

    /**
     * @var int
     *
     * @ORM\Column(name="realloc_user", type="smallint")
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "You can't put something under 0.",
     * )
     */
    private $reallocUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime", nullable=true)
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime", nullable=true)
     */
    private $end;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="anonymous_mode", type="smallint")
     */
    private $anonymousMode;

    public function __construct($nbMaxUser, $reallocUser)
    {
        $this->creationDate = new \Datetime();
        $this->nbMaxUser = $nbMaxUser;
        $this->reallocUser = $reallocUser;
        $this->status = 0;
        $this->anonymousMode = 0;
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
     * Set Mission
     *
     * @param \MissionBundle\Entity\Mission $mission
     * @return Step
     */
    public function setMission($mission)
    {
        $this->mission = $mission;
        $mission->addStep($this);

        return $this;
    }

    /**
     * Get Mission
     *
     * @return \MissionBundle\Entity\Mission
     */
    public function getMisison()
    {
        return $this->Mission;
    }

    /**
     * Set Position
     *
     * @param integer $position
     * @return Step
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get Position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Step
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
     * @return Step
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
     * Set start
     *
     * @param \DateTime $start
     * @return Step
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Step
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Step
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

    /**
     * Set AnonymousMode
     *
     * @param integer $mode
     * @return Step
     */
    public function setAnonymousMode($mode)
    {
        $this->anonymousMode = $mode;

        return $this;
    }

    /**
     * Get AnonymousMode
     *
     * @return integer
     */
    public function getAnonymousMode()
    {
        return $this->anonymousMode;
    }

    /**
     * Set nbMaxUser
     *
     * @param integer $nbMaxUser
     * @return Step
     */
    public function setNbMaxUser($nbMaxUser)
    {
        $this->nbMaxUser = $nbMaxUser;

        return $this;
    }

    /**
     * Get nbMaxUser
     *
     * @return integer
     */
    public function getNbMaxUser()
    {
        return $this->nbMaxUser;
    }

    /**
     * Set reallocUser
     *
     * @param integer $reallocUser
     * @return Step
     */
    public function setReallocUser($reallocUser)
    {
        $this->reallocUser = $reallocUser;

        return $this;
    }

    /**
     * Get reallocUser
     *
     * @return integer
     */
    public function getReallocUser()
    {
        return $this->reallocUser;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdateDate(new \Datetime());
    }

    /**
     * Get mission
     *
     * @return \MissionBundle\Entity\Mission
     */
    public function getMission()
    {
        return $this->mission;
    }
}
