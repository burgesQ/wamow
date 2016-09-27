<?php

namespace MissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use ToolsBundle\Entity\Language;
use ToolsBundle\Form\LanguageType;
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
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     * @Assert\Regex(
     *     pattern="#^[0-9a-zA-Zéèêëçîïíàáâñńœôö]+(?:[\s-][a-zA-Zéèêëçîïíàáâñńœôö]+)*$#",
     *     match=true,
     *     message="The address must contain only letters numbers, point, comma or dash.")
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     * @Assert\Regex(
     *     pattern="#^[a-zA-Zéèêëçîïíàáâñńœôö]+(?:[\s-][a-zA-Zéèêëçîïíàáâñńœôö]+)*$#",
     *     match=true,
     *     message="The city must contain only letters or dash.")
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=255)
     */
    private $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var bool
     *
     * @ORM\Column(name="confidentiality", type="boolean")
     */
    private $confidentiality;

    /**
     * @var int
     *
     * @ORM\Column(name="number_step", type="smallint")
     * @Assert\Range(
     *      min = 1,
     *      max = 5,
     *      minMessage = "There is at least {{ limit }}step.",
     *      maxMessage = "There is no more than {{ limit }}steps."
     * )
     */
    private $numberStep;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=true)
     * @Assert\Regex(
     *     pattern="#^[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$#",
     *     match=true,
     *     message="The state must contain only letters or dash.")
     */
    private $state;

    /**
     * @var int
     *
     * @ORM\Column(name="ID_contact", type="integer", nullable=true)
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
    private $language;

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
     *      max = 99999,
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
     *      max = 9999,
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

    public function __construct()
      {
        $this->creationDate = new \Datetime();
        $this->UpdateDate = new \DateTime();
        $this->language = new ArrayCollection();
        $this->status = 0;
        $this->numberStep = 3;
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
     * Set address
     *
     * @param string $address
     * @return Mission
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Mission
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Mission
     */
    public function setCountry($country)
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
     * Set state
     *
     * @param string $state
     * @return Mission
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
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
     * Set beginning
     *
     * @param \DateTime $beginning
     * @return Mission
     */
    public function setBeginning($beginning)
    {
        $this->beginning = $beginning;

        return $this;
    }

    /**
     * Get beginning
     *
     * @return \DateTime
     */
    public function getBeginning()
    {
        return $this->beginning;
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
     * Set zipcode
     *
     * @param string $zipcode
     * @return Mission
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Add language
     *
     * @param \ToolsBundle\Entity\Language $language
     * @return Mission
     */
    public function addLanguage(\ToolsBundle\Entity\Language $language)
    {
        $this->language[] = $language;

        return $this;
    }

    /**
     * Remove language
     *
     * @param \ToolsBundle\Entity\Language $language
     */
    public function removeLanguage(\ToolsBundle\Entity\Language $language)
    {
        $this->language->removeElement($language);
    }

    /**
     * Get language
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set language
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $language
     * @return Mission
     */
    public function setLanguage(ArrayCollection $language)
    {
        $this->language = $language;
    }


    /**
     * Set ending
     *
     * @param \DateTime $ending
     * @return Mission
     */
    public function setEnding($ending)
    {
        $this->ending = $ending;

        return $this;
    }

    /**
     * Get ending
     *
     * @return \DateTime
     */
    public function getEnding()
    {
        return $this->ending;
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
}
