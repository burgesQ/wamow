<?php

namespace MissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use ToolsBundle\Entity\Language;
use ToolsBundle\Entity\Address;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Mission
 *
 * @ORM\Table(name="mission")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\MissionRepository")
 */
class Mission
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\Regex(
     *     pattern="#^[a-zA-Zéèêëçîïíàáâñńœôö]+(?:[\s-][a-zA-Zéèêëçîïíàáâñńœôö]+)*$#",
     *     match=true,
     *     message="The title must contain only letters, numbers, point, comma or dash.")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="text")
     * @Assert\Length(
     *      max = 4000)
     */
    private $resume;

    /**
     * @ORM\OneToOne(targetEntity="ToolsBundle\Entity\Address", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $address;

    /**
     * @var bool
     *
     * @ORM\Column(name="confidentiality", type="boolean")
     */
    private $confidentiality;

    /**
     * @var int
     *
     * @ORM\Column(name="ID_contact", type="integer")
     */
    private $iDContact;

    /**
     * @var int
     *
     * @ORM\Column(name="ID_company", type="integer", nullable=true)
     */
    private $iDCompany;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

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
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="\ToolsBundle\Entity\Language", cascade={"persist"})
     */
    private $languages;

    /**
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\ProfessionalExpertise")
     * @ORM\JoinColumn(nullable=false)
     */
    private $professionalExpertise;

    /**
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\MissionKind")
     * @ORM\JoinColumn(nullable=false)
     */
    private $missionKind;

    /**
     * @var bool
     *
     * @ORM\Column(name="telecommuting", type="boolean")
     */
    private $telecommuting;

    /**
     * @var bool
     *
     * @ORM\Column(name="international", type="boolean")
     */
    private $international;

    /**
     * @var int
     *
     * @ORM\Column(name="daily_fees_min", type="integer")
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "You need to fill this field.",
     * )
     */
    private $dailyFeesMin;

    /**
     * @var int
     *
     * @ORM\Column(name="daily_fees_max", type="integer")
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "You need to fill this field.",
     * )
     */
    private $dailyFeesMax;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="beginning", type="datetime")
     */
    private $missionBeginning;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ending", type="datetime")
     */
    private $missionEnding;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="applicationEnding", type="datetime")
     */
    private $applicationEnding;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Assert\Url(
     *    message = "The url '{{ value }}' is not a valid url",
     * )
     */
    private $image;

    /**
      * @var int
      *
     * @ORM\Column(name="number_step", type="smallint")
     */
    private $numberStep;

    /**
      * @var string
      *
     * @ORM\Column(name="token", type="string", length=255, nullable=false, unique=true)
     */
    private $token;

    /**
      * @var int
      *
     * @ORM\Column(name="size_team_max", type="smallint")
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "The max size of users in a team must be at least one.")
     */
    private $sizeTeamMax;

    public function __construct($nbStep, $iDContact, $sizeTeamMax, $token)
      {
        $this->creationDate = new \Datetime();
        $this->updateDate = new \DateTime();
        $this->languages = new ArrayCollection();
        $this->status = 0;
        $this->address = new Address();
        $this->numberStep = $nbStep;
        $this->iDContact = $iDContact;
        $this->sizeTeamMax = $sizeTeamMax;
        $this->token = $token;
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
     * Set title
     *
     * @param string $title
     * @return Mission
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set resume
     *
     * @param string $resume
     * @return Mission
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
     * Get address
     *
     * @return \ToolsBundle\Entity\Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address
     *
     * @param \ToolsBundle\Entity\Address $Address
     * @return Mission
     */
    public function setAddress(Address $address = null)
    {
        $this->address = $address;
    }

    /**
     * Set confidentiality
     *
     * @param boolean $confidentiality
     * @return Mission
     */
    public function setConfidentiality($confidentiality)
    {
        $this->confidentiality = $confidentiality;

        return $this;
    }

    /**
     * Get confidentiality
     *
     * @return boolean
     */
    public function getConfidentiality()
    {
        return $this->confidentiality;
    }

    /**
     * Set iDContact
     *
     * @param integer $iDContact
     * @return Mission
     */
    public function setIDContact($iDContact)
    {
        $this->iDContact = $iDContact;

        return $this;
    }

    /**
     * Get iDContact
     *
     * @return integer
     */
    public function getIDContact()
    {
        return $this->iDContact;
    }

    /**
     * Set iDCompany
     *
     * @param integer $iDCompany
     * @return Mission
     */
    public function setIDCompany($iDCompany)
    {
        $this->iDCompany = $iDCompany;

        return $this;
    }

    /**
     * Get iDCompany
     *
     * @return integer
     */
    public function getIDCompany()
    {
        return $this->iDCompany;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Mission
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Mission
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
     * @return Mission
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
     * Set telecommuting
     *
     * @param boolean $telecommuting
     * @return Mission
     */
    public function setTelecommuting($telecommuting)
    {
        $this->telecommuting = $telecommuting;

        return $this;
    }

    /**
     * Get telecommuting
     *
     * @return boolean
     */
    public function getTelecommuting()
    {
        return $this->telecommuting;
    }

    /**
     * Set dailyFeesMin
     *
     * @param integer $dailyFeesMin
     * @return Mission
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
     * @return Mission
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
     * Set image
     *
     * @param string $image
     * @return Mission
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set international
     *
     * @param boolean $international
     * @return Mission
     */
    public function setInternational($international)
    {
        $this->international = $international;

        return $this;
    }

    /**
     * Get international
     *
     * @return boolean
     */
    public function getInternational()
    {
        return $this->international;
    }

    /**
     * Set missionBeginning
     *
     * @param \DateTime $missionBeginning
     * @return Mission
     */
    public function setMissionBeginning($missionBeginning)
    {
        $this->missionBeginning = $missionBeginning;

        return $this;
    }

    /**
     * Get missionBeginning
     *
     * @return \DateTime
     */
    public function getMissionBeginning()
    {
        return $this->missionBeginning;
    }

    /**
     * Set missionEnding
     *
     * @param \DateTime $missionEnding
     * @return Mission
     */
    public function setMissionEnding($missionEnding)
    {
        $this->missionEnding = $missionEnding;

        return $this;
    }

    /**
     * Get missionEnding
     *
     * @return \DateTime
     */
    public function getMissionEnding()
    {
        return $this->missionEnding;
    }

    /**
     * Set applicationEnding
     *
     * @param \DateTime $applicationEnding
     * @return Mission
     */
    public function setApplicationEnding($applicationEnding)
    {
        $this->applicationEnding = $applicationEnding;

        return $this;
    }

    /**
     * Get applicationEnding
     *
     * @return \DateTime
     */
    public function getApplicationEnding()
    {
        return $this->applicationEnding;
    }

    /**
     * Set professionalExpertise
     *
     * @param \MissionBundle\Entity\ProfessionalExpertise $professionalExpertise
     * @return Mission
     */
    public function setProfessionalExpertise(\MissionBundle\Entity\ProfessionalExpertise $professionalExpertise)
    {
        $this->professionalExpertise = $professionalExpertise;

        return $this;
    }

    /**
     * Get professionalExpertise
     *
     * @return \MissionBundle\Entity\ProfessionalExpertise
     */
    public function getProfessionalExpertise()
    {
        return $this->professionalExpertise;
    }

    /**
     * Set missionKind
     *
     * @param \MissionBundle\Entity\MissionKind $missionKind
     * @return Mission
     */
    public function setMissionKind(\MissionBundle\Entity\MissionKind $missionKind)
    {
        $this->missionKind = $missionKind;

        return $this;
    }

    /**
     * Get missionKind
     *
     * @return \MissionBundle\Entity\MissionKind
     */
    public function getMissionKind()
    {
        return $this->missionKind;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        $missionBeginning = $this->getMissionBeginning();
        $missionEnding = $this->getMissionEnding();
        $applicationEnding = $this->getApplicationEnding();
        $dailyFeesMin = $this->getDailyFeesMin();
        $dailyFeesMax = $this->getDailyFeesMax();
        $today = new \DateTime();
        if ($missionBeginning->format("yy/mm/dd") > $missionEnding->format("yy/mm/dd"))
        {
          $context
            ->buildViolation('The mission can not start after the end date.')
            ->atPath('missionBeginning')
            ->addViolation()
            ;
        }
        elseif ($applicationEnding->format("yy/mm/dd") > $missionBeginning->format("yy/mm/dd"))
        {
          $context
            ->buildViolation('The deadline must be before the mission start.')
            ->atPath('applicationEnding')
            ->addViolation()
            ;
        }
        elseif ($missionBeginning <= $today
                || $missionEnding <= $today
                || $applicationEnding <= $today)
        {
            $context
              ->buildViolation('You can\'t pick a past date.')
              ->atPath('missionBeginning')
              ->addViolation()
              ;
        }
        if ($dailyFeesMin > $dailyFeesMax)
        {
          $context
            ->buildViolation('The minimum fees must be less than the maximum.')
            ->atPath('dailyFeesMin')
            ->addViolation()
            ;
        }
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
     * @param \ToolsBundle\Entity\Language $languages
     * @return Mission
     */
    public function addLanguage(\ToolsBundle\Entity\Language $languages)
    {
        $this->languages[] = $languages;

        return $this;
    }

    /**
     * Remove languages
     *
     * @param \ToolsBundle\Entity\Language $languages
     */
    public function removeLanguage(\ToolsBundle\Entity\Language $languages)
    {
        $this->languages->removeElement($languages);
    }

    /**
      * Set numberStep
      *
      * @param integer $numberStep
      * @return Mission
      */
     public function setNumberStep($numberStep)
     {
         $this->numberStep = $numberStep;

         return $this;
     }

     /**
      * Get numberStep
      *
      * @return integer
      */
     public function getNumberStep()
     {
         return $this->numberStep;
     }

    /**
     * Set token
     *
     * @param string $token
     * @return Mission
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set sizeTeamMax
     *
     * @param integer $sizeTeamMax
     * @return Mission
     */
    public function setSizeTeamMax($sizeTeamMax)
    {
        $this->sizeTeamMax = $sizeTeamMax;

        return $this;
    }

    /**
     * Get sizeTeamMax
     *
     * @return integer
     */
    public function getSizeTeamMax()
    {
        return $this->sizeTeamMax;
    }
}
