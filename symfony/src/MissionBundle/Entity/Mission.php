<?php

namespace MissionBundle\Entity;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Mission
 *
 * @ORM\Table(name="mission")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\MissionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Mission
{
    // PITCH GENERATOR STATUS
    const STEP_ZERO  = 0;
    const STEP_ONE   = 1;
    const STEP_TWO   = 2;
    const STEP_THREE = 3;
    const STEP_FOUR  = 4;
    const DONE       = 5;

    // MISSION STATUS
    const DELETED   = -1;
    const DRAFT     = 0;
    const PUBLISHED = 1;

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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\Regex(
     *     pattern="#^[a-zA-Zéèêëçîïíàáâñńœôö]+(?:[\s-][a-zA-Zéèêëçîïíàáâñńœôö]+)*$#",
     *     match=true,
     *     message="error.mission.title.illegale"
     * )
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="text", nullable=true)
     */
    private $resume;

    /**
     * @ORM\OneToOne(targetEntity="ToolsBundle\Entity\Address", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
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
     * @ORM\Column(name="update_date", type="datetime", nullable=false)
     */
    private $updateDate;

    /**
     * @ORM\ManyToMany(targetEntity="\ToolsBundle\Entity\Language", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $languages;

    /**
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\ProfessionalExpertise", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
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
     * @ORM\JoinColumn(nullable=true)
     */
    private $businessPractice;

    /**
     * @var bool
     *
     * @ORM\Column(name="telecommuting", type="boolean", nullable=true)
     */
    private $telecommuting;

    /**
     * @var int
     *
     * @ORM\Column(name="budget", type="integer", nullable=true)
     * @Assert\Range(min = 1)
     */
    private $budget;

    /**
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\CompanySize")
     * @ORM\JoinColumn(nullable=true)
     */
    private $companySize;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="beginning", type="datetime", nullable=true)
     */
    private $missionBeginning;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ending", type="datetime", nullable=true)
     */
    private $missionEnding;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="applicationEnding", type="datetime", nullable=true)
     */
    private $applicationEnding;

    /**
      * @var int
      *
     * @ORM\Column(name="number_step", type="smallint", nullable=false)
     */
    private $numberStep;

    /**
     * @ORM\OneToMany(targetEntity="MissionBundle\Entity\UserMission", mappedBy="mission")
     */
    private $userMission;

    /**
     * @ORM\OneToMany(targetEntity="InboxBundle\Entity\Thread", mappedBy="mission")
     */
    private $threads;

    /**
     * @var integer
     *
     * @ORM\Column(name="status_generator", type="integer", nullable=false)
     */
    private $statusGenerator;

    /**
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\Continent")
     */
    private $continents;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     * @Assert\Range(min = 1)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="user.fname.blank", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=2,
     *     max=255
     * )
     * @Assert\Regex(
     *  pattern="/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð '-]+$/",
     *  message="user.fname.illegale"
     * )
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="user.lname.blank", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=2,
     *     max=255
     * )
     * @Assert\Regex(
     *  pattern="/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð '-]+$/",
     *  message="user.lname.illegale"
     * )
     */
    private $lastName;

    /**
     * @var bool
     *
     * @ORM\Column(name="share", type="boolean", nullable=true)
     */
    private $share;

    /**
     * @var bool
     *
     * @ORM\Column(name="reference", type="boolean", nullable=true)
     */
    private $reference;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CompanyBundle\Entity\Inspector", cascade={"persist"}, inversedBy="missions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $inspectors;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\Certification", cascade={"persist"}, inversedBy="missions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $certifications;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="MissionBundle\Entity\WorkExperience",
     *     inversedBy="missions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $workExperience;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MissionBundle\Entity\Step",
     *      mappedBy="mission"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $steps;

    /**
     * @ORM\Column(name="scoring_history", type="json_array", nullable=true)
     */
    private $scoringHistory;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="next_update_scoring", type="date", nullable=true)
     */
    private $nextUpdateScoring;

    /**

     * @var string
     * @ORM\Column(name="public_id", type="string", nullable=true, unique=true)
     */
    private $publicId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="on_draft", type="boolean", nullable=false)
     */
    private $onDraft;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_ongoing", type="integer", nullable=false)
     */
    private $nbOngoing;

    /**
     * Mission constructor.
     *
     * @param $nbStep
     * @param $user
     * @param $company
     */
    public function __construct($nbStep, $user, $company)
    {
        $this->numberStep      = $nbStep;
        $this->contact         = $user;
        $this->company         = $company;
        $this->status          = self::DRAFT;
        $this->statusGenerator = self::STEP_ZERO;
        $this->creationDate    = new \Datetime();
        $this->updateDate      = new \Datetime();
        $this->languages       = new ArrayCollection();
        $this->userMission     = new ArrayCollection();
        $this->threads         = new ArrayCollection();
        $this->inspectors      = new ArrayCollection();
        $this->certifications  = new ArrayCollection();
        $this->missionKinds    = new ArrayCollection();
        $this->continents      = new ArrayCollection();
        $this->confidentiality = true;
        $this->telecommuting   = false;
        $this->share           = true;
        $this->reference       = true;
        $this->address         = null;
        $this->price           = 1;
        $this->nbOngoing       = 0;
        $this->onDraft         = false;
    }

    /**
     * @Assert\Callback
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->getOnDraft() === false) {
            if ($this->getStatusGenerator() == self::STEP_ZERO) {
                if (substr_count($this->getResume(), ' ') >= 500) {
                    $context
                        ->buildViolation('error.resume.max_words')
                        ->atPath('resume')->addViolation()
                    ;
                }
            } elseif ($this->getStatusGenerator() == self::STEP_ONE) {
                $missionBeginning = $this->getMissionBeginning();
                $missionEnding = $this->getMissionEnding();
                $applicationEnding = $this->getApplicationEnding();
                $today = new \DateTime();
                if ($missionBeginning <= $today || $missionEnding <= $today || $applicationEnding <= $today) {
                    $context
                        ->buildViolation('error.date.past_date')
                        ->atPath('missionBeginning')->addViolation()
                    ;
                } elseif ($missionBeginning->format("yy/mm/dd") >= $missionEnding->format("yy/mm/dd")) {
                    $context
                        ->buildViolation('error.date.after_end_date')
                        ->atPath('missionBeginning')->addViolation()
                    ;
                } elseif ($applicationEnding->format("yy/mm/dd")
                          >= $missionBeginning->format("yy/mm/dd")) {
                    $context
                        ->buildViolation('error.date.before_start_date')
                        ->atPath('applicationEnding')->addViolation()
                    ;
                } elseif ($this->getMissionEnding()) {
                    $this->setPrice(($this->getBudget() * 1000) / $this->getMissionEnding()
                            ->diff($this->getMissionBeginning())
                            ->format('%a'));
                }
            } elseif ($this->getStatusGenerator() == self::STEP_TWO) {
                if (!count($this->getMissionKinds())) {
                    $context
                        ->buildViolation('error.mission_kind.one')
                        ->atPath('missionKinds')->addViolation()
                    ;
                } elseif (!count($this->getLanguages())) {
                    $context
                        ->buildViolation('error.languages.one')
                        ->atPath('languages')->addViolation()
                    ;
                }
            }
        }
    }

    public function __toString()
    {
     return $this->id . ' : ' . $this->title;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdateDate(new \Datetime());
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
     *
     * @return Mission
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Set company
     *
     * @param \CompanyBundle\Entity\Company $company
     * @return Mission
     */
    public function setCompany($company)
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
    public function setProfessionalExpertise($professionalExpertise)
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
     * Get missionKind
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMissionKinds()
    {
        return $this->missionKinds;
    }

    /**
     * Add missionKind
     *
     * @param \MissionBundle\Entity\MissionKind $missionKind
     *
     * @return $this
     */
    public function removeMissionKind($missionKind)
    {
        $this->missionKinds->removeElement($missionKind);

        return $this;
    }

    /**
     * Add missionKinds
     *
     * @param \MissionBundle\Entity\MissionKind $missionKinds
     * @return Mission
     */
    public function addMissionKind($missionKinds)
    {
        $this->missionKinds[] = $missionKinds;

        return $this;
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
     * Set businessPractice
     *
     * @param \MissionBundle\Entity\BusinessPractice $businessPractice
     * @return Mission
     */
    public function setBusinessPractice($businessPractice)
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

    /**
     * Set statusGenerator
     *
     * @param integer $statusGenerator
     *
     * @return Mission
     */
    public function setStatusGenerator($statusGenerator)
    {
        $this->statusGenerator = $statusGenerator;

        return $this;
    }

    /**
     * Get statusGenerator
     *
     * @return integer
     */
    public function getStatusGenerator()
    {
        return $this->statusGenerator;
    }

    /**
     * Add continents
     *
     * @param \MissionBundle\Entity\Continent $continents
     * @return Mission
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

    public function getActualStep()
    {
        foreach ($this->steps as $step) {
            if ($step->getStatus() === 1) {
                return $step;
            }
        }
        return null;
    }

    public function passNextStep()
    {
        $actualStep = $this->getActualStep();
        $position = $actualStep->getPosition() + 1;
        $actualStep->setStatus(0);
        foreach ($this->steps as $step) {
            if ($position === $step->getPosition()) {
                $step->setStatus(1);
                // Cleaning userMissions
                $userMissions = $this->getUserMission();
                foreach ($userMissions as $userMission) {
                    switch ($position) {
                        case 2:
                            # SHORTLIST
                            if ($userMission->getStatus() == UserMission::ONGOING) {
                                $userMission->setStatus(UserMission::DISMISS);
                            }
                            break;
                        case 3:
                            # FINALIST
                            if ($userMission->getStatus() == UserMission::SHORTLIST) {
                                $userMission->setStatus(UserMission::DISMISS);
                            }
                            break;
                    }
                }
                return;
            }
        }
        throw new \Exception("No step found after position ".$actualStep->getPosition());
    }

    /**
     * Get steps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * Add step
     *
     * @param \MissionBundle\Entity\Step $step
     * @return Mission
     */
    public function addStep($step)
    {
        $this->steps[] = $step;

        return $this;
    }

    /**
     * Remove step
     *
     * @param \MissionBundle\Entity\Step $step
     */
    public function removeStep($step)
    {
        $this->steps->removeElement($step);
    }

    /**
     * Set scoringHistory
     *
     * @return Mission
     */
    public function setScoringHistory($scoringHistory)
    {
        $this->scoringHistory = $scoringHistory;

        return $this;
    }

    /**
     * Get scoringHistory
     *
     */
    public function getScoringHistory()
    {
        return $this->scoringHistory;
    }

    /**
     * Set nextUpdateScoring
     *
     * @param \DateTime $nextUpdateScoring
     * @return Mission
     */
    public function setNextUpdateScoring($nextUpdateScoring)
    {
        $this->nextUpdateScoring = $nextUpdateScoring;

        return $this;
    }

    /**
     * Get nextUpdateScoring
     *
     * @return \DateTime
     */
    public function getNextUpdateScoring()
    {
        return $this->nextUpdateScoring;
    }

     /**
     * Set Price
     *
     * @param integer $price
     *
     * @return Mission
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get Price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Mission
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Mission
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set share
     *
     * @param boolean $share
     *
     * @return Mission
     */
    public function setShare($share)
    {
        $this->share = $share;

        return $this;
    }

    /**
     * Get share
     *
     * @return boolean
     */
    public function getShare()
    {
        return $this->share;
    }

    /**
     * Set reference
     *
     * @param boolean $reference
     *
     * @return Mission
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return boolean
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Add languages
     *
     * @param \ToolsBundle\Entity\Language $languages
     *
     * @return Mission
     */
    public function addLanguage($languages)
    {
        $this->languages[] = $languages;

        return $this;
    }

    /**
     * Remove languages
     *
     * @param \ToolsBundle\Entity\Language $languages
     */
    public function removeLanguage($languages)
    {
        $this->languages->removeElement($languages);
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
     * Set contact
     *
     * @param \UserBundle\Entity\User $contact
     * @return Mission
     */
    public function setContact($contact = null)
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
     * Add inspector
     *
     * @param \CompanyBundle\Entity\Inspector $inspector
     * @return Mission
     */
    public function addInspector($inspector)
    {
        $inspector->addMission($this);
        $this->inspectors[] = $inspector;

        return $this;
    }

    /**
     * Remove inspector
     *
     * @param \CompanyBundle\Entity\Inspector $inspector
     */
    public function removeInspector($inspector)
    {
        $this->inspectors->removeElement($inspector);
    }

    /**
     * Get inspectors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInspectors()
    {
        return $this->inspectors;
    }

    /**
     * Add certifications
     *
     * @param \MissionBundle\Entity\Certification $certifications
     * @return Mission
     */
    public function addCertification($certifications)
    {
        $this->certifications[] = $certifications;

        return $this;
    }

    /**
     * Remove certifications
     *
     * @param \MissionBundle\Entity\Certification $certifications
     */
    public function removeCertification($certifications)
    {
        $this->certifications->removeElement($certifications);
    }

    /**
     * Get certifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCertifications()
    {
        return $this->certifications;
    }

    /**
     * Set publicId
     *
     * @param string $publicId
     * @return Mission
     */
    public function setPublicId($publicId)
    {
        $this->publicId = $publicId;

        return $this;
    }

    /**
     * Get publicId
     *
     * @return string
     */
    public function getPublicId()
    {
        return $this->publicId;
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
     * Set onDraft
     *
     * @param boolean $onDraft
     * @return Mission
     */
    public function setOnDraft($onDraft)
    {
        $this->onDraft = $onDraft;

        return $this;
    }

    /**
     * Get onDraft
     *
     * @return boolean
     */
    public function getOnDraft()
    {
        return $this->onDraft;
    }

    /**
     * Set workExperience
     *
     * @param \MissionBundle\Entity\WorkExperience $workExperience
     * @return Mission
     */
    public function setWorkExperience(WorkExperience $workExperience = null)
    {
        if ($workExperience) {
            $workExperience->addMission($this);
        }
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
     * Set companySize
     *
     * @param \MissionBundle\Entity\CompanySize $companySize
     * @return Mission
     */
    public function setCompanySize(CompanySize $companySize = null)
    {
        $this->companySize = $companySize;

        return $this;
    }

    /**
     * Get companySize
     *
     * @return \MissionBundle\Entity\CompanySize
     */
    public function getCompanySize()
    {
        return $this->companySize;
    }

    /**
     * Set nbOngoing
     *
     * @param integer $nbOngoing
     * @return Mission
     */
    public function setNbOngoing($nbOngoing)
    {
        $this->nbOngoing = $nbOngoing;

        return $this;
    }

    /**
     * Get nbOngoing
     *
     * @return integer 
     */
    public function getNbOngoing()
    {
        return $this->nbOngoing;
    }
}
