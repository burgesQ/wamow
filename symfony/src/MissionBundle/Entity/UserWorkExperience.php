<?php

namespace MissionBundle\Entity;

use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserWorkExperience
 *
 * @ORM\Table(name="user_work_experience")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\UserWorkExperienceRepository")
 */
class UserWorkExperience
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
     * @ORM\ManyToOne(
     *     targetEntity="MissionBundle\Entity\WorkExperience",
     *     cascade={"persist"},
     *     inversedBy="userWorkExperiences"
     * )
     */
    private $workExperience;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="UserBundle\Entity\User",
     *     cascade={"persist"},
     *     inversedBy="userWorkExperiences"
     * )
     */
    private $user;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="MissionBundle\Entity\CompanySize",
     *     inversedBy="userWorkExperiences"
     * )
     */
    private $companySize;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="MissionBundle\Entity\Continent",
     *     inversedBy="userWorkExperiences"
     * )
     */
    private $continents;

    /**
     * @var int
     *
     * @ORM\Column(name="cumuled_month", type="integer")
     * @Assert\Length(
     *     min=0,
     *     max=240,
     *     minMessage="The value enter is too small",
     *     maxMessage="The value enter is too big",
     * )
     */
    private $cumuledMonth;

    /**
     * @var int
     *
     * @ORM\Column(name="daily_fees", type="integer")
     * @Assert\Length(
     *     min=0,
     *     max=5000,
     *     minMessage="The value enter is too small",
     *     maxMessage="The value enter is too big",
     * )
     */
    private $dailyFees;

    /**
     * @var bool
     *
     * @ORM\Column(name="peremption", type="boolean")
     */
    private $peremption;

    /**
     * UserWorkExperience constructor.
     */
    public function __construct()
    {
        $this->companySize    = new ArrayCollection();
        $this->continents     = new ArrayCollection();
        $this->cumuledMonth   = 1;
        $this->dailyFees      = 0;
        $this->peremption     = false;
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
     * Set workExperience
     *
     * @param \MissionBundle\Entity\WorkExperience $workExperience
     * @return UserWorkExperience
     */
    public function setWorkExperience($workExperience)
    {
        $workExperience->addUserWorkExperience($this);
        $this->workExperience = $workExperience;

        return $this;
    }

    /**
     * Get workExperience
     *
     * @return \MissionBundle\Entity\WorkExperience
     */
    public function getWorkExperience()
    {
        return $this->workExperience;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return UserWorkExperience
     */
    public function setUser($user)
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
     * Set cumuledMonth
     *
     * @param integer $cumuledMonth
     * @return UserWorkExperience
     */
    public function setCumuledMonth($cumuledMonth)
    {
        $this->cumuledMonth = $cumuledMonth;

        return $this;
    }

    /**
     * Get cumuledMonth
     *
     * @return integer
     */
    public function getCumuledMonth()
    {
        return $this->cumuledMonth;
    }

    /**
     * Set dailyFees
     *
     * @param integer $dailyFees
     * @return UserWorkExperience
     */
    public function setDailyFees($dailyFees)
    {
        $this->dailyFees = $dailyFees;

        return $this;
    }

    /**
     * Get dailyFees
     *
     * @return integer
     */
    public function getDailyFees()
    {
        return $this->dailyFees;
    }

    /**
     * Set peremption
     *
     * @param boolean $peremption
     * @return UserWorkExperience
     */
    public function setPeremption($peremption)
    {
        $this->peremption = $peremption;

        return $this;
    }

    /**
     * Get peremption
     *
     * @return boolean
     */
    public function getPeremption()
    {
        return $this->peremption;
    }

    /**
     * Add companySize
     *
     * @param \MissionBundle\Entity\CompanySize $companySize
     * @return UserWorkExperience
     */
    public function addCompanySize($companySize)
    {
        $companySize->addUserWorkExperience($this);
        $this->companySize[] = $companySize;

        return $this;
    }

    /**
     * Remove companySize
     *
     * @param \MissionBundle\Entity\CompanySize $companySize
     */
    public function removeCompanySize($companySize)
    {
        $companySize->removeUserWorkExperience($this);
        $this->companySize->removeElement($companySize);
    }

    /**
     * Get companySize
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanySize()
    {
        return $this->companySize;
    }

    /**
     * Add continents
     *
     * @param \MissionBundle\Entity\Continent $continents
     * @return UserWorkExperience
     */
    public function addContinent($continents)
    {
        $continents->addUserWorkExperience($this);
        $this->continents[] = $continents;

        return $this;
    }

    /**
     * Remove continents
     *
     * @param \MissionBundle\Entity\Continent $continents
     */
    public function removeContinent($continents)
    {
        $continents->removeUserWorkExperience($this);
        $this->continents->removeElement($continents);
    }

    /**
     * Get continents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContinents()
    {
        return $this->continents;
    }
}
