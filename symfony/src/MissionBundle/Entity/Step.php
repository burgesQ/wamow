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
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\Mission", cascade={"persist"})
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
     * @ORM\Column(name="nb_max_team", type="smallint")
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "You can't put something under 1.",
     * )
     */
    private $nbMaxTeam;

    /**
     * @var int
     *
     * @ORM\Column(name="realloc_team", type="smallint")
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "You can't put something under 0.",
     * )
     */
    private $reallocTeam;

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

    public function __construct($nbMaxTeam, $reallocTeam)
    {
        $this->creationDate = new \Datetime();
        $this->nbMaxTeam = $nbMaxTeam;
        $this->reallocTeam = $reallocTeam;
        $this->status = 0;
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
     * @param integer $Position
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
     * Set nbMaxTeam
     *
     * @param integer $nbMaxTeam
     * @return Step
     */
    public function setNbMaxTeam($nbMaxTeam)
    {
        $this->nbMaxTeam = $nbMaxTeam;

        return $this;
    }

    /**
     * Get nbMaxTeam
     *
     * @return integer
     */
    public function getNbMaxTeam()
    {
        return $this->nbMaxTeam;
    }

    /**
     * Set reallocTeam
     *
     * @param integer $reallocTeam
     * @return Step
     */
    public function setReallocTeam($reallocTeam)
    {
        $this->reallocTeam = $reallocTeam;

        return $this;
    }

    /**
     * Get reallocTeam
     *
     * @return integer
     */
    public function getReallocTeam()
    {
        return $this->reallocTeam;
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

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdateDate(new \Datetime());
    }
}
