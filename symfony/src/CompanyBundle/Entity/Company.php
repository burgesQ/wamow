<?php

namespace CompanyBundle\Entity;

use MissionBundle\Entity\BusinessPractice;
use ToolsBundle\Entity\Address;
use UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Company
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
     * @ORM\Column(name="name", type="string", length=255, unique=true, nullable=false)
     */
    private $name;

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
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=false)
     */
    private $logo;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="text", nullable=true)
     */
    private $resume;

    /**
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\BusinessPractice")
     * @ORM\JoinColumn(nullable=false)
     */
    private $businessPractice;

    /**
     * @var Address
     * @ORM\OneToOne(targetEntity="ToolsBundle\Entity\Address", cascade={"persist"})
     */
    private $address;

    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->status = 0;
    }

    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Company
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
     * Set logo
     *
     * @param string $logo
     * @return Company
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Company
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
     * Set resume
     *
     * @param string $resume
     * @return Company
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return Company
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
     * Set businessPractice
     *
     * @param \MissionBundle\Entity\BusinessPractice $businessPractice
     * @return Company
     */
    public function setBusinessPractice(\MissionBundle\Entity\BusinessPractice $businessPractice)
    {
        $this->businessPractice = $businessPractice;

        return $this;
    }

    /**
     * Get businessPractice
     *
     * @return \MissionBundle\Entity\BusinessPractice
     */
    public function getBusinessPractice()
    {
        return $this->businessPractice;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdateDate(new \Datetime());
    }

    /**
     * Set address
     *
     * @param \ToolsBundle\Entity\Address $address
     * @return Company
     */
    public function setAddress($address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \ToolsBundle\Entity\Address
     */
    public function getAddress()
    {
        return $this->address;
    }
}
