<?php

namespace MissionBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ExperienceShaping
 *
 * @ORM\Table(name="experience_shaping")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\ExperienceShapingRepository")
 */
class ExperienceShaping
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
     *     inversedBy="experienceShaping"
     * )
     */
    private $workExperience;

    /**
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\CompanySize")
     */
    private $companySize;

    /**
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\Continent")
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
     * ExperienceShaping constructor.
     */
    public function __construct()
    {
        $this->companySize    = new ArrayCollection();
        $this->continents     = new ArrayCollection();
        $this->cumuledMonth   = 1;
        $this->dailyFees      = 0;
        $this->peremption     = false;
        $this->workExperience = null;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * Set workExperience
     *
     * @param MissionBundle /Entity/WorkExperience $workExperience
     *
     * @return ExperienceShaping
     */
    public function setWorkExperience($workExperience)
    {
        $this->workExperience = $workExperience;

        return $this;
    }

    /**
     * Add companySize
     *
     * @param \MissionBundle\Entity\CompanySize $companySize
     * @return ExperienceShaping
     */
    public function addCompanySize($companySize)
    {
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
     * @return ExperienceShaping
     */
    public function addContinent($continents)
    {
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

    /**
     * Get cumuledMonth
     *
     * @return int
     */
    public function getCumuledMonth()
    {
        return $this->cumuledMonth;
    }

    /**
     * Set cumuledMonth
     *
     * @param integer $cumuledMonth
     *
     * @return ExperienceShaping
     */
    public function setCumuledMonth($cumuledMonth)
    {
        $this->cumuledMonth = $cumuledMonth;

        return $this;
    }

    /**
     * Get dailyFees
     *
     * @return int
     */
    public function getDailyFees()
    {
        return $this->dailyFees;
    }

    /**
     * Set dailyFees
     *
     * @param integer $dailyFees
     *
     * @return ExperienceShaping
     */
    public function setDailyFees($dailyFees)
    {
        $this->dailyFees = $dailyFees;

        return $this;
    }

    /**
     * Get peremption
     *
     * @return bool
     */
    public function getPeremption()
    {
        return $this->peremption;
    }

    /**
     * Set peremption
     *
     * @param boolean $peremption
     *
     * @return ExperienceShaping
     */
    public function setPeremption($peremption)
    {
        $this->peremption = $peremption;

        return $this;
    }
}
