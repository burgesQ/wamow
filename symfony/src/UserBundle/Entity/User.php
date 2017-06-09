<?php

namespace UserBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use FOS\MessageBundle\Model\ParticipantInterface;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser implements ParticipantInterface
{
    const REGISTER_STEP_ZERO      = 0;
    const REGISTER_STEP_ONE       = 1;
    const REGISTER_STEP_TWO       = 2;
    const REGISTER_STEP_THREE     = 3;
    const REGISTER_STEP_FOUR      = 4;
    const REGISTER_NO_STEP        = 5;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="linkedin_id", type="string", length=255, nullable=true)
     */
    protected $linkedin_id;

    /**
     * @ORM\Column(name="linkedin_access_token", type="string", length=255, nullable=true)
     */
    protected $linkedin_access_token;

    /**
     * @ORM\Column(name="linkedin_data", type="array", nullable=true)
     */
    protected $linkedinData;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @Assert\Regex(
     *  pattern="/^(?=.*[a-z])/",
     *  message="user.pass.lowercase"
     * )
     * @Assert\Regex(
     *  pattern="/^(?=.*[A-Z])/",
     *  message="user.pass.upppercase"
     * )
     * @Assert\Regex(
     *  pattern="/^(?=.*\d)/",
     *  message="user.pass.number"
     * )
     * @Assert\Regex(
     *  pattern="/^(?=.*\W)/",
     *  message="user.pass.spechar"
     * )
     * @Assert\NotBlank(
     *  message="fos_user.password.blank",
     *  groups={"Registration", "ResetPassword", "ChangePassword"}
     * )
     * @Assert\Length(
     *  min=8,
     *  minMessage="fos_user.password.short",
     *  groups={"Registration", "Profile", "ResetPassword", "ChangePassword"}
     * )
     */
    protected $plainPassword;

    /**
     * @var bool
     *
     * @ORM\Column(name="confidentiality", type="boolean", nullable=false)
     */
    private $confidentiality;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="user.fname.blank", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="user.fname.short",
     *     maxMessage="user.fname.long",
     *     groups={"Registration", "Profile"} )
     * @Assert\Regex(
     *  pattern="/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð '-]+$/",
     *  message="user.fname.illegale" )
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="user.lname.blank", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="user.lname.short",
     *     maxMessage="user.lname.long",
     *     groups={"Registration", "Profile"} )
     * @Assert\Regex(
     *  pattern="/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð '-]+$/",
     *  message="user.lname.illegale" )
     */
    private $lastName;

    /**
     * @var int
     *
     * @ORM\Column(name="daily_fees_min", type="bigint", nullable=true)
     * @Assert\Range(
     *      min = 0
     *)
     */
    private $dailyFeesMin;

    /**
     * @var int
     *
     * @ORM\Column(name="daily_fees_max", type="bigint", nullable=true)
     * @Assert\Range(
     *      min = 0
     *)
     */
    private $dailyFeesMax;

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
     * @ORM\OneToOne(targetEntity="ToolsBundle\Entity\PhoneNumber", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="CompanyBundle\Entity\Company", cascade={"persist"})
     * @ORM\joinColumn(onDelete="SET NULL")
     */
    private $company;

    /**
     * @var bool
     *
     * @ORM\Column(name="newsletter", type="boolean", nullable=false)
     */
    private $newsletter;

    /**
     * @var int
     *
     * @ORM\Column(name="give_up_count", type="integer")
     */
    private $giveUpCount;

    /**
     * @var array
     *
     * @ORM\Column(name="secretMail", type="array", nullable=false)
     */
    private $secretMail;

    /**
     * @ORM\OneToMany(targetEntity="ToolsBundle\Entity\ProfilePicture", mappedBy="user", cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="ToolsBundle\Entity\UploadResume",
     *     mappedBy="user", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $resumes;

    /**
     * @var string
     *
     * @ORM\Column(name="user_resume", type="text", nullable=true)
     */
    private $userResume;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="\ToolsBundle\Entity\Language", cascade={"persist"})
     * @Assert\Count(
     *     min=1,
     *     minMessage="user.languages.min"
     * )
     */
    private $languages;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\ProfessionalExpertise", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $professionalExpertise;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\MissionKind", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $missionKind;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\BusinessPractice", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $businessPractice;

    /**
     * @ORM\Column(name="email_emergency", type="string", length=255, nullable=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    protected $emergencyEmail;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="\MissionBundle\Entity\UserWorkExperience",
     *     mappedBy="user",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $userWorkExperiences;

    /**
     * @ORM\OneToMany(
     *     targetEntity="MissionBundle\Entity\UserMission",
     *     mappedBy="user"
     * )
     */
    private $userMission;

    /**
     * @var int
     *
     * @ORM\Column(name="scoring_bonus", type="integer")
     */
    private $scoringBonus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="remote_work", type="boolean", nullable=false)
     */
    private $remoteWork;

    /**
     * @var string
     *
     * @ORM\Column(name="public_id", type="string", nullable=true, unique=true)
     */
    private $publicId;

    /**
     * @ORM\OneToMany(
     *     targetEntity="ToolsBundle\Entity\Address",
     *     mappedBy="user",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $addresses;

    /**
     * @var boolean
     *
     * @orm\Column(nullable=false, name="payment", type="boolean")
     */
    private $payment;

    /**
     * @var bool
     * @ORM\Column(name="notification", type="boolean", nullable=false)
     */
    private $notification;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\Certification", cascade={"persist"}, inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    private $certifications;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="plan_subscribed_at", type="datetime", nullable=true)
     */
    private $planSubscripbedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="plan_expires_at", type="datetime", nullable=true)
     */
    private $planExpiresAt;

    /**
     * @var string
     *
     * @ORM\Column(name="plan_type", type="string", length=255, nullable=true)
     */
    private $planType;

    /**
     * @var int
     *
     * @ORM\Column(name="plan_payment_amount", type="integer", nullable=true)
     */
    private $planPaymentAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="plan_payment_provider", type="string", length=255, nullable=true)
     */
    private $planPaymentProvider;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->status                = self::REGISTER_STEP_ZERO;
        $this->creationDate          = new \Datetime();
        $this->images                = new ArrayCollection();
        $this->resumes               = new ArrayCollection();
        $this->languages             = new ArrayCollection();
        $this->userWorkExperiences   = new ArrayCollection();
        $this->professionalExpertise = new ArrayCollection();
        $this->missionKind           = new ArrayCollection();
        $this->businessPractice      = new ArrayCollection();
        $this->addresses             = new ArrayCollection();
        $this->secretMail            = new ArrayCollection();
        $this->userMission           = new ArrayCollection();
        $this->certifications        = new ArrayCollection();
        $this->confidentiality       = false;
        $this->payment               = false;
        $this->remoteWork            = false;
        $this->newsletter            = true;
        $this->notification          = true;
        $this->userResume            = null;
        $this->company               = null;
        $this->publicId              = "";
        $this->giveUpCount           = 0;
        $this->secretMail            = [];
        $this->linkedinData          = [];
        $this->scoringBonus          = 5;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdateDate(new \Datetime());
    }

    /**
     * @Assert\Callback
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     */
    public function isValidate($context)
    {
        $feesMin = $this->getDailyFeesMin();
        $feesMax = $this->getDailyFeesMax();

        if ($this->getPhone() != NULL) {
            $this->getPhone()->isValidate($context);
        } elseif ($feesMin == NULL && $feesMax != NULL) {
            $context
                ->buildViolation('user.minfees.unset')
                ->atPath('dailyFeesMin')
                ->addViolation();
        } elseif ($feesMax == NULL && $feesMin != NULL) {
            $context
                ->buildViolation('user.maxfees.unset')
                ->atPath('dailyFeesMax')
                ->addViolation();
        } elseif ($feesMin != NULL && $feesMax != NULL && $feesMin >= $feesMax) {
            $context
                ->buildViolation('user.minfees.over')
                ->atPath('dailyFeesMin')
                ->addViolation();
        }
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);

        return $this;
    }

    /**
     * Set company
     *
     * @param \CompanyBundle\Entity\Company $company
     * @return User
     */
    public function setCompany($company = null)
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
     * @return User
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
     * Set confidentiality
     *
     * @param boolean $confidentiality
     * @return User
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
     * Set firstName
     *
     * @param string $firstName
     * @return User
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
     * @return User
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
     * Set phone
     *
     * @param \ToolsBundle\Entity\PhoneNumber $phone
     * @return User
     */
    public function setPhone($phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return \ToolsBundle\Entity\PhoneNumber
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Add images
     *
     * @param \ToolsBundle\Entity\ProfilePicture $image
     * @return User
     */
    public function addImage($image)
    {
        $image->setUser($this);
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \ToolsBundle\Entity\ProfilePicture $image
     */
    public function removeImage($image)
    {
        $this->images->removeElement($image);
    }


    /**
     * Get images
     *
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set newsletter
     *
     * @param boolean $newsletter
     * @return User
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;
        return $this;
    }

    /**
     * Get newsletter
     *
     * @return boolean
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set linkedin_id
     *
     * @param string $linkedinId
     * @return User
     */
    public function setLinkedinId($linkedinId)
    {
        $this->linkedin_id = $linkedinId;

        return $this;
    }

    /**
     * Get linkedin_id
     *
     * @return string
     */
    public function getLinkedinId()
    {
        return $this->linkedin_id;
    }

    /**
     * Set linkedin_access_token
     *
     * @param string $linkedinAccessToken
     * @return User
     */
    public function setLinkedinAccessToken($linkedinAccessToken)
    {
        $this->linkedin_access_token = $linkedinAccessToken;

        return $this;
    }

    /**
     * Get linkedin_access_token
     *
     * @return string
     */
    public function getLinkedinAccessToken()
    {
        return $this->linkedin_access_token;
    }

    /**
     * Set giveUpCount
     *
     * @param integer $giveUpCount
     * @return User
     */
    public function setGiveUpCount($giveUpCount)
    {
        $this->giveUpCount = $giveUpCount;

        return $this;
    }

    /**
     * Get giveUpCount
     *
     * @return integer
     */
    public function getGiveUpCount()
    {
        return $this->giveUpCount;
    }

    /**
     * Add addSecretMail
     *
     * @param string $mail
     * @return User
     */
    public function addSecretMail($mail)
    {
        $this->secretMail[] = $mail;
        return $this;
    }

    /**
     * Get secretMail
     *
     * @return array
     */
    public function getSecretMail()
    {
        return $this->secretMail;
    }

    /**
     * Set secretMail
     *
     * @param array $secretMail
     *
     * @return User
     */
    public function setSecretMail($secretMail)
    {
        $this->secretMail = $secretMail;

        return $this;
    }

    /**
     * Remove resume
     *
     * @param \ToolsBundle\Entity\UploadResume $resume
     */
    public function removeResume($resume)
    {
        $this->resumes->removeElement($resume);
    }

    /**
     * Add resume
     *
     * @param \ToolsBundle\Entity\UploadResume $resume
     * @return User
     */
    public function addResume($resume = null)
    {
        if ($this->getId() > 0) {
            $resume->setUser($this);
        }
        $this->resumes[] = $resume;

        return $this;
    }

    /**
     * Reset resumes
     *
     * @return User
     */
    public function resetResumes()
    {
        $this->resumes = new ArrayCollection();

        return $this;
    }

    /**
     * Get resume
     *
     * @return ArrayCollection
     */
    public function getResumes()
    {
        return $this->resumes;
    }

    /**
     * Set userResume
     *
     * @param string $resume
     * @return User
     */
    public function setUserResume($resume)
    {
        $this->userResume = $resume;
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
     * Get languages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Add language
     *
     * @param \ToolsBundle\Entity\Language $language
     * @return User
     */
    public function addLanguage($language)
    {
        $this->languages[] = $language;

        return $this;
    }

    /**
     * Remove language
     *
     * @param \ToolsBundle\Entity\Language $language
     */
    public function removeLanguage($language)
    {
        $this->languages->removeElement($language);
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
     * Add ProfessionalExpertise
     *
     * @param \MissionBundle\Entity\ProfessionalExpertise $professionalExpertise
     * @return User
     */
    public function addProfessionalExpertise($professionalExpertise)
    {
        $this->professionalExpertise[] = $professionalExpertise;

        return $this;
    }

    /**
     * Remove ProfessionalExpertise
     *
     * @param \MissionBundle\Entity\ProfessionalExpertise $professionalExpertise
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
     * @param \MissionBundle\Entity\MissionKind $missionKind
     * @return User
     */
    public function addMissionKind($missionKind)
    {
        $this->missionKind[] = $missionKind;

        return $this;
    }

    /**
     * Remove MissionKind
     *
     * @param \MissionBundle\Entity\MissionKind $missionKind
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
     * @param \MissionBundle\Entity\BusinessPractice $businessPractice
     * @return User
     */
    public function addBusinessPractice($businessPractice)
    {
        $this->businessPractice[] = $businessPractice;

        return $this;
    }

    /**
     * Remove BusinessPractice
     *
     * @param \MissionBundle\Entity\BusinessPractice $businessPractice
     */
    public function removeBusinessPractice($businessPractice)
    {
        $this->businessPractice->removeElement($businessPractice);
    }

    /**
     * Set emergencyEmail
     *
     * @param string $emergencyEmail
     *
     * @return User
     */
    public function setEmergencyEmail($emergencyEmail)
    {
        $this->emergencyEmail = $emergencyEmail;

        return $this;
    }

    /**
     * Get emergencyEmail
     *
     * @return string
     */
    public function getEmergencyEmail()
    {
        return $this->emergencyEmail;
    }

    /**
     * Add workExperience
     *
     * @param \MissionBundle\Entity\UserWorkExperience $workExperience
     * @return User
     */
    public function addUserWorkExperience($workExperience)
    {
        $this->userWorkExperiences[] = $workExperience;

        return $this;
    }

    /**
     * Remove userWorkExperience
     *
     * @param \MissionBundle\Entity\UserWorkExperience $workExperience
     */
    public function removeUserWorkExperience($workExperience)
    {
        $this->userWorkExperiences->removeElement($workExperience);
    }

    /**
     * Get userWorkExperiences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserWorkExperiences()
    {
        return $this->userWorkExperiences;
    }

    /**
     * Reset userWorkExperiences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function resetUserWorkExperiences()
    {
        unset($this->userWorkExperiences);
        $this->userWorkExperiences = new ArrayCollection();

        return $this->userWorkExperiences;
    }

    /**
     * Add userMission
     *
     * @param \MissionBundle\Entity\UserMission $userMission
     * @return User
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
     * Set scoringBonus
     *
     * @param integer $scoringBonus
     * @return User
     */
    public function setScoringBonus($scoringBonus)
    {
        $this->scoringBonus = $scoringBonus;

        return $this;
    }

    /**
     * Get scoringBonus
     *
     * @return integer
     */
    public function getScoringBonus()
    {
        return $this->scoringBonus;
    }


    /**
     * Set remoteWork
     *
     * @param boolean $remoteWork
     * @return User
     */
    public function setRemoteWork($remoteWork)
    {
        $this->remoteWork = $remoteWork;

        return $this;
    }

    /**
     * Get remoteWork
     *
     * @return boolean
     */
    public function getRemoteWork()
    {
        return $this->remoteWork;
    }

    /**
     * Set publicId
     *
     * @param string $publicId
     * @return User
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
     * Set linkedinData
     *
     * @param array $linkedinData
     * @return User
     */
    public function setLinkedinData($linkedinData)
    {
        $this->linkedinData = $linkedinData;

        return $this;
    }

    /**
     * Get LinkedinData
     *
     * @return array
     */
    public function getLinkedinData()
    {
        return $this->linkedinData;
    }

    /**
     * Remove address
     *
     * @param \ToolsBundle\Entity\Address $address
     *
     * @return $this
     */
    public function removeAddress($address)
    {
        $address->setUser(null);
        $this->addresses->removeElement($address);

        return $this;
    }

    /**
     * Get address
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Add address
     *
     * @param \ToolsBundle\Entity\Address $address
     * @return User
     */
    public function addAddress($address)
    {
        $address->setUser($this);
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Set payement
     *
     * @param boolean $payment
     * @return User
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return boolean
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set notification
     *
     * @param boolean $notification
     * @return User
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return boolean
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Add certifications
     *
     * @param \MissionBundle\Entity\Certification $certifications
     * @return User
     */
    public function addCertification($certifications)
    {
        $certifications->addUser($this);
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
        $certifications->removeUser($this);
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
     * Set planSubscripbedAt
     *
     * @param \DateTime $planSubscripbedAt
     * @return User
     */
    public function setPlanSubscripbedAt($planSubscripbedAt)
    {
        $this->planSubscripbedAt = $planSubscripbedAt;

        return $this;
    }

    /**
     * Get planSubscripbedAt
     *
     * @return \DateTime
     */
    public function getPlanSubscripbedAt()
    {
        return $this->planSubscripbedAt;
    }

    /**
     * Set planExpiresAt
     *
     * @param \DateTime $planExpiresAt
     * @return User
     */
    public function setPlanExpiresAt($planExpiresAt)
    {
        $this->planExpiresAt = $planExpiresAt;

        return $this;
    }

    /**
     * Get planExpiresAt
     *
     * @return \DateTime
     */
    public function getPlanExpiresAt()
    {
        return $this->planExpiresAt;
    }

    /**
     * Set planType
     *
     * @param string $planType
     * @return User
     */
    public function setPlanType($planType)
    {
        $this->planType = $planType;

        return $this;
    }

    /**
     * Get planType
     *
     * @return string
     */
    public function getPlanType()
    {
        return $this->planType;
    }

    /**
     * Set planPaymentAmount
     *
     * @param integer $planPaymentAmount
     * @return User
     */
    public function setPlanPaymentAmount($planPaymentAmount)
    {
        $this->planPaymentAmount = $planPaymentAmount;

        return $this;
    }

    /**
     * Get planPaymentAmount
     *
     * @return integer
     */
    public function getPlanPaymentAmount()
    {
        return $this->planPaymentAmount;
    }

    /**
     * Set planPaymentProvider
     *
     * @param string $planPaymentProvider
     * @return User
     */
    public function setPlanPaymentProvider($planPaymentProvider)
    {
        $this->planPaymentProvider = $planPaymentProvider;

        return $this;
    }

    /**
     * Get planPaymentProvider
     *
     * @return string
     */
    public function getPlanPaymentProvider()
    {
        return $this->planPaymentProvider;
    }
}
