<?php

namespace CompanyBundle\Entity;

use CompanyBundle\Entity\Sector;
use UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="CompanyBundle\Repository\CompanyRepository")
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
     * @ORM\Column(name="update_date", type="datetime", nullable=false)
     */
    private $updateDate;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer", nullable=false)
     * @Assert\Range(
     *      min = 1
     *)
     */

    private $size;

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
     * @ORM\ManyToOne(targetEntity="CompanyBundle\Entity\Sector", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */

    private $sector;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */

    private $type;

    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->creationDate = new \Datetime();
        $this->updateDate = new \Datetime();
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
     * Set size
     *
     * @param integer $size
     * @return Company
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Company
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set sector
     *
     * @param \CompanyBundle\Entity\Sector $sectorId
     * @return Company
     */
    public function setSector(Sector $sector = null)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get sector
     *
     * @return \CompanyBundle\Entity\Sector
     */
    public function getSector()
    {
        return $this->sector;
    }
}