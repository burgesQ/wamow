<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * ElasticUser
 *
 * @ORM\Table(name="elastic_user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\ElasticUserRepository")
 */
class ElasticUser
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
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

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
     * @var ArrayCollection
     *
     * @ORM\Column(name="languages", type="array", nullable=true)
     */
    private $languages;
    
    /**
     * @var ArrayCollection
     * 
     * @ORM\Column(name="professional_expertise", type="array", nullable=true)
     */
    private $professionalExpertise;

    /**
     * @var ArrayCollection
     * 
     * @ORM\Column(name="mission_kind", type="array", nullable=true)
     */
    private $missionKind;

    /**
     * @var ArrayCollection
     * 
     * @ORM\Column(name="business_practice", type="array", nullable=true)
     */
    private $businessPractice;

    /**
     * @var text
     *
     * @ORM\Column(name="cv_resume", type="text", nullable=true)
     */
    private $cvResume;

    /**
     * @var text
     *
     * @ORM\Column(name="user_resume", type="text", nullable=true)
     */
    private $userResume;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", nullable=false)
     */
    private $country;

    /**
     * @var int
     *
     * @ORM\Column(name="daily_fees_min", type="bigint", nullable=true)
     * @Assert\Range(
     *      min = 0 )
     */
    private $dailyFeesMin;

    /**
     * @var int
     *
     * @ORM\Column(name="daily_fees_max", type="bigint", nullable=true)
     * @Assert\Range(
     *      min = 0 )
     */
    private $dailyFeesMax;
    
    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->updateDate = new \Datetime();
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return ElasticUser
     */
    public function setUserId($userId = null)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return User
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
     * @return User
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
     * Get languages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Add languages
     *
     * @param string $languages
     * @return ElasticUser
     */
    public function addLanguage($languages)
    {
        $this->languages[] = $languages;

        return $this;
    }

    /**
     * Remove languages
     *
     * @param string $languages
     */
    public function removeLanguage($languages)
    {
        $this->languages->removeElement($languages);
    }

    /**
     * Get ProfessionalExpertise
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProfessionalExpertise()
    {
        return $this->professionalExpertise;
    }

    /**
     * Add Professionalexpertise
     *
     * @param string $professionalExpertise
     * @return ElasticUser
     */
    public function addProfessionalExpertise($professionalExpertise)
    {
        $this->professionalExpertise[] = $professionalExpertise;

        return $this;
    }

    /**
     * Remove ProfessionalExpertise
     *
     * @param string $professionalExpertise
     */
    public function removeProfessionalExpertise($professionalExpertise)
    {
        $this->professionalExpertise->removeElement($professionalExpertise);
    }

    /**
     * Get MissionKind
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMissionKind()
    {
        return $this->missionKind;
    }

    /**
     * Add MissionKind
     *
     * @param string $missionKind
     * @return ElasticUser
     */
    public function addMissionKind($missionKind)
    {
        $this->missionKind[] = $missionKind;

        return $this;
    }

    /**
     * Remove MissionKind
     *
     * @param string $missionKind
     */
    public function removeMissionKind($missionKind)
    {
        $this->missionKind->removeElement($missionKind);
    }

    /**
     * Get BusinessPractice
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBusinessPractice()
    {
        return $this->businessPractice;
    }

    /**
     * Add BusinessPractice
     *
     * @param string $businessPractice
     * @return ElasticUser
     */
    public function addBusinessPractice($businessPractice)
    {
        $this->businessPractice[] = $businessPractice;

        return $this;
    }

    /**
     * Remove BusinessPractice
     *
     * @param string $businessPractice
     */
    public function removeBusinessPractice($businessPractice)
    {
        $this->businessPractice->removeElement($businessPractice);
    }
    
    /**
     * Set cvResume
     * 
     * @param string $cvResume
     *
     * @return ElasticUser
     */
    public function setCvResume($cvResume = null)
    {
        $this->cvResume = $cvResume;

        return $this;
    }

    /**
     * Get cvResume
     *
     * @return string
     */
    public function getCvResume()
    {
        return $this->cvResume;
    }

    /**
     * Set userResume
     *
     * @param string $userResume
     *
     * @return ElasticUser
     */
    public function setUserResume($userResume = null)
    {
        $this->userResume = $userResume;

        return $this;
    }

    /**
     * Get userResume
     *
     * @return string
     */
    public function getUserResume()
    {
        return $this->userResume;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return ElasticUser
     */
    public function setCountry($country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set dailyFeesMin
     *
     * @param integer $dailyFeesMin
     * @return User
     */
    public function setDailyFeesMin($dailyFeesMin)
    {
        $this->dailyFeesMin = $dailyFeesMin;

        return $this;
    }

    /**
     * Get dailyFeesMin
     *
     * @return integer
     */
    public function getDailyFeesMin()
    {
        return $this->dailyFeesMin;
    }

    /**
     * Set dailyFeesMax
     *
     * @param integer $dailyFeesMax
     * @return User
     */
    public function setDailyFeesMax($dailyFeesMax)
    {
        $this->dailyFeesMax = $dailyFeesMax;

        return $this;
    }

    /**
     * Get dailyFeesMax
     *
     * @return integer
     */
    public function getDailyFeesMax()
    {
        return $this->dailyFeesMax;
    }
    
    /**
     * @Assert\Callback
     */
    public function isValidate(ExecutionContextInterface $context)
    {
        $this->setUpdateDate(new \Datetime());
    }
    
}
