<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ExperienceShaping
 *
 * @ORM\Table(name="experience_shaping")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\ExperienceShapingRepository")
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
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\WorkExperience", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $workTitle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="small_company", type="boolean")
     */
    private $smallCompany;

    /**
     * @var boolean
     *
     * @ORM\Column(name="medium_company", type="boolean")
     */
    private $mediumCompany;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="large_company", type="boolean")
     */
    private $largeCompany;

    /**
     * @var boolean
     *
     * @ORM\Column(name="south_america", type="boolean")
     */
    private $southAmerica;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="north_america", type="boolean")
     */
    private $northAmerica;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="asia", type="boolean")
     */
    private $asia;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="emea", type="boolean")
     */
    private $emea;

    /**
     * @var int
     *
     * @ORM\Column(name="cumuled_month", type="integer")
     * @Assert\Length(
     *     min=0, 
     *     max=240,
     *     minMessage="The value enter is too small",
     *     maxMessage="The valur enter is too big",
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
    
    public function __construct($workTitle)
    {
        $this->smallCompany = false;
        $this->mediumCompany = false;
        $this->largeCompany = false;
        $this->southAmerica = false;
        $this->northAmerica = false;
        $this->asia = false;
        $this->emea = false;
        $this->cumuledMonth = 0;
        $this->dailyFees = 0;
        $this->peremption = false;
        $this->workTitle = $workTitle;
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
     * Set workTitle
     *
     * @param MissionBundle/Entity/WorkExperience $workTitle
     *
     * @return ExperienceShaping
     */
    public function setWorkTitle($workTitle)
    {
        $this->workTitle = $workTitle;

        return $this;
    }

    /**
     * Get workTitle
     *
     * @return MissionBundle/Entity/WorkExperience
     */
    public function getWorkTitle()
    {
        return $this->workTitle;
    }

    /**
     * Set smallCompany
     *
     * @param boolean $smallCompany
     *
     * @return ExperienceShaping
     */
    public function setSmallCompany($smallCompany)
    {
        $this->smallCompany = $smallCompany;

        return $this;
    }

    /**
     * Get smallCompany
     *
     * @return boolean
     */
    public function getSmallCompany()
    {
        return $this->smallCompany;
    }

    /**
     * Set mediumCompany
     *
     * @param boolean $mediumCompany
     *
     * @return ExperienceShaping
     */
    public function setMediumCompany($mediumCompany)
    {
        $this->mediumCompany = $mediumCompany;

        return $this;
    }

    /**
     * Get mediumCompany
     *
     * @return boolean
     */
    public function getMediumCompany()
    {
        return $this->mediumCompany;
    }

    /**
     * Set largeCompany
     *
     * @param boolean $largeCompany
     *
     * @return ExperienceShaping
     */
    public function setLargeCompany($largeCompany)
    {
        $this->largeCompany = $largeCompany;

        return $this;
    }

    /**
     * Get largeCompany
     *
     * @return boolean
     */
    public function getLargeCompany()
    {
        return $this->largeCompany;
    }

    /**
     * Set southAmerica
     *
     * @param boolean $southAmerica
     *
     * @return ExperienceShaping
     */
    public function setSouthAmerica($southAmerica)
    {
        $this->southAmerica = $southAmerica;

        return $this;
    }

    /**
     * Get southAmerica
     *
     * @return boolean
     */
    public function getSouthAmerica()
    {
        return $this->southAmerica;
    }

    /**
     * Set northAmerica
     *
     * @param boolean $northAmerica
     *
     * @return ExperienceShaping
     */
    public function setNorthAmerica($northAmerica)
    {
        $this->northAmerica = $northAmerica;

        return $this;
    }

    /**
     * Get northAmerica
     *
     * @return boolean
     */
    public function getNorthAmerica()
    {
        return $this->northAmerica;
    }

    /**
     * Set asia
     *
     * @param boolean $asia
     *
     * @return ExperienceShaping
     */
    public function setAsia($asia)
    {
        $this->asia = $asia;

        return $this;
    }

    /**
     * Get asia
     *
     * @return boolean
     */
    public function getAsia()
    {
        return $this->asia;
    }
    
    /**
     * Set emea
     *
     * @param boolean $emea
     *
     * @return ExperienceShaping
     */
    public function setEmea($emea)
    {
        $this->emea = $emea;

        return $this;
    }

    /**
     * Get emea
     *
     * @return boolean
     */
    public function getEmea()
    {
        return $this->emea;
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
     * Get cumuledMonth
     *
     * @return int
     */
    public function getCumuledMonth()
    {
        return $this->cumuledMonth;
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
     * Get dailyFees
     *
     * @return int
     */
    public function getDailyFees()
    {
        return $this->dailyFees;
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

    /**
     * Get peremption
     *
     * @return bool
     */
    public function getPeremption()
    {
        return $this->peremption;
    }
}

