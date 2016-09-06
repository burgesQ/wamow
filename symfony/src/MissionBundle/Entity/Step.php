<?php

namespace MissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Step
 *
 * @ORM\Table(name="step")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\StepRepository")
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
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\Mission")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mission;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="smallint")
     */
    private $position;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
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
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    private $endDate;

    /**
     * @var int
     *
     * @ORM\Column(name="max_number_team", type="smallint", nullable=true)
     */
    private $maxNumberTeam;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    public function __construct()
      {
        $this->creationDate = new \Datetime();
        $this->UpdateDate = new \DateTime();
        $this->startDate = new \DateTime();
        $this->endDate = new \DateTime();
        $this->maxNumberTeam = 4;
        $this->status = 0;
        $this->position = 1;
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

    public function setMission($mission)
    {
        $this->mission = $mission;

        return $this;
    }

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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Step
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Step
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set maxNumberTeam
     *
     * @param integer $maxNumberTeam
     * @return Step
     */
    public function setMaxNumberTeam($maxNumberTeam)
    {
        $this->maxNumberTeam = $maxNumberTeam;

        return $this;
    }

    /**
     * Get maxNumberTeam
     *
     * @return integer
     */
    public function getMaxNumberTeam()
    {
        return $this->maxNumberTeam;
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
}
