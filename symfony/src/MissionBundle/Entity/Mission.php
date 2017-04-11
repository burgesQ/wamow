<?php

namespace MissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use ToolsBundle\Entity\Language;
use ToolsBundle\Entity\Address;
use UserBundle\Entity\User;
use ToolsBundle\Entity\Tag;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Mission
 *
 * @ORM\Table(name="mission")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\MissionRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="contact")
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity="CompanyBundle\Entity\Company", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

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
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="\ToolsBundle\Entity\Language", cascade={"persist"})
     */
    private $languages;

    /**
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\ProfessionalExpertise", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $professionalExpertise;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\MissionKind", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $missionKinds;

    /**
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\BusinessPractice")
     * @ORM\JoinColumn(nullable=false)
     */
    private $businessPractice;

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
     * @ORM\Column(name="budget", type="integer")
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "You can't put something under 0.",
     * )
     */
    private $budget;

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
     * @ORM\ManyToMany(targetEntity="ToolsBundle\Entity\Tag", cascade={"persist"})
     */
    private $tags;

    /**
     * @ORM\OneToMany(
     *     targetEntity="MissionBundle\Entity\UserMission",
     *     mappedBy="mission"
     * )
     */
    private $userMission;

    /**
     * @ORM\OneToMany(
     *     targetEntity="InboxBundle\Entity\Thread",
     *     mappedBy="mission"
     * )
     */
    private $threads;

    /**
     * Mission constructor.
     *
     * @param $nbStep
     * @param $user
     * @param $token
     * @param $company
     */
    public function __construct($nbStep, $user, $token, $company)
    {
        $this->creationDate = new \Datetime();
        $this->languages    = new ArrayCollection();
        $this->status       = 0;
        $this->address      = new Address();
        $this->numberStep   = $nbStep;
        $this->contact      = $user;
        $this->token        = $token;
        $this->company      = $company;
        $this->userMission  = new ArrayCollection();
        $this->threads      = new ArrayCollection();
    }

    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    public function getTags()
    {
        return $this->tags;
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
     * @param \ToolsBundle\Entity\Address $address
     * @return Mission
     */
    public function setAddress(Address $address)
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
     * Set Contact
     *
     * @param \UserBundle\Entity\User $contact
     * @return Mission
     */
    public function setContact(\UserBundle\Entity\User $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \UserBundle\Entity\User
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set company
     *
     * @param \CompanyBundle\Entity\Company $company
     * @return Mission
     */
    public function setCompany(\CompanyBundle\Entity\Company $company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \CompanyBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
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
     * Add missionKind
     *
     * @param \MissionBundle\Entity\MissionKind $missionKind
     *
     * @return Mission
     */
    public function addMissionKind($missionKind)
    {
        $this->missionKinds[] = $missionKind;

        return $this;
    }

    /**
     * Remove missionKind
     *
     * @param \MissionBundle\Entity\MissionKind $missionKind
     *
     * @return Mission
     */
    public function removeMissionKind($missionKind)
    {
        $this->missionKinds->removeElement($missionKind);

        return $this;
    }

    /**
     * Set missionKinds
     *
     * @param ArrayCollection $missionKinds
     * @return Mission
     */
    public function setMissionKinds($missionKinds)
    {
        $this->missionKinds = $missionKinds;

        return $this;
    }

    /**
     * Get missionKinds
     *
     * @return ArrayCollection
     */
    public function getMissionKinds()
    {
        return $this->missionKinds;
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
     * Set businessPractice
     *
     * @param \MissionBundle\Entity\BusinessPractice $businessPractice
     * @return Mission
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
     * Set budget
     *
     * @param integer $budget
     * @return Mission
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return integer
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        $missionBeginning = $this->getMissionBeginning();
        $missionEnding = $this->getMissionEnding();
        $applicationEnding = $this->getApplicationEnding();
        $today = new \DateTime();
        if ($missionBeginning->format("yy/mm/dd") >= $missionEnding->format("yy/mm/dd"))
        {
          $context
            ->buildViolation('The mission can not start after the end date.')
            ->atPath('missionBeginning')
            ->addViolation()
            ;
        }
        elseif ($applicationEnding->format("yy/mm/dd") >= $missionBeginning->format("yy/mm/dd"))
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
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdateDate(new \Datetime());
    }

    /**
     * Add userMission
     *
     * @param \MissionBundle\Entity\UserMission $userMission
     * @return Mission
     */
    public function addUserMission($userMission)
    {
        $this->userMission[] = $userMission;

        return $this;
    }

    /**
     * Remove userMission
     *
     * @param \MissionBundle\Entity\UserMission $userMission
     */
    public function removeUserMission($userMission)
    {
        $this->userMission->removeElement($userMission);
    }

    /**
     * Get userMission
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserMission()
    {
        return $this->userMission;
    }

    /**
     * Add threads
     *
     * @param \InboxBundle\Entity\Thread $threads
     * @return Mission
     */
    public function addThread($threads)
    {
        $this->threads[] = $threads;

        return $this;
    }

    /**
     * Remove threads
     *
     * @param \InboxBundle\Entity\Thread $threads
     */
    public function removeThread($threads)
    {
        $this->threads->removeElement($threads);
    }

    /**
     * Get threads
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getThreads()
    {
        return $this->threads;
    }
}
