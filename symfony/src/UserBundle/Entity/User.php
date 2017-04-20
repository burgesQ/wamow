<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\MessageBundle\Model\ParticipantInterface;

use CompanyBundle\Entity\Company;

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
     * @ORM\Column(name="password_set", type="boolean", nullable=false)
     */
    private $password_set;

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
     * @ORM\Column(name="gender", type="smallint", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      max = 2
     *)
     */
    private $gender;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime", nullable=true)
     */
    private $birthdate;

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
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

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
     * @ORM\OneToMany(targetEntity="ToolsBundle\Entity\ProfilePicture", mappedBy="user")
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="ToolsBundle\Entity\UploadResume",
     *     mappedBy="user", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $resumes;

    /**
     * @var text
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
     * @var int
     *
     * @ORM\Column(name="nb_load", type="smallint", nullable=false)
     * @Assert\Range(
     *      min = 10,
     *      max = 1000,
     *      minMessage = "You must load at least {{ limit }} messages",
     *      maxMessage = "You cannot load more than {{ limit }} messaxges"
     * )
     */
    private $nbLoad;

    /**
     * @var boolean
     *
     * @ORM\Column(name="read_report", type="boolean", nullable=false)
     */
    private $readReport;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="\MissionBundle\Entity\WorkExperience", cascade={"persist"})
     */
    private $workExperience;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\ExperienceShaping", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $experienceShaping;

    /**
     * @ORM\OneToMany(
     *     targetEntity="MissionBundle\Entity\UserMission",
     *     mappedBy="user"
     * )
     */
    private $userMission;

    /**
     * @var boolean
     *
     * @ORM\Column(name="remote_work", type="boolean", nullable=false)
     */
    private $remoteWork;

    /**
     * @var string
     * @ORM\Column(name="public_id", type="string", nullable=true, unique=true)
     */
    private $publicId;

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
        $this->workExperience        = new ArrayCollection();
        $this->professionalExpertise = new ArrayCollection();
        $this->missionKind           = new ArrayCollection();
        $this->businessPractice      = new ArrayCollection();
        $this->experienceShaping     = new ArrayCollection();
        $this->userMission           = new ArrayCollection();
        $this->confidentiality       = false;
        $this->remoteWork            = false;
        $this->newsletter            = true;
        $this->readReport            = true;
        $this->userResume            = null;
        $this->birthdate             = null;
        $this->company               = null;
        $this->publicId              = "";
        $this->nbLoad                = 10;
        $this->giveUpCount           = 0;
        $this->secretMail            = [];
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
    public function setCompany(Company $company = null)
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
     * Set gender
     *
     * @param integer $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Get gender
     *
     * @return integer
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return User
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
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
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdateDate(new \Datetime());
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
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
    public function setPhone(\ToolsBundle\Entity\PhoneNumber $phone = null)
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
     * @param \ToolsBundle\Entity\ProfilePicture image
     * @return User
     */
    public function addImage($images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \ToolsBundle\Entity\ProfilePicture $images
     */
    public function removeImage($images)
    {
        $this->images->removeElement($images);
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
     * Set password_set
     *
     * @param boolean $passwordSet
     * @return User
     */
    public function setPasswordSet($passwordSet)
    {
        $this->password_set = $passwordSet;

        return $this;
    }

    /**
     * Get password_set
     *
     * @return boolean
     */
    public function isPasswordSet()
    {
        return $this->password_set;
    }

    /**
     * Get password_set
     *
     * @return boolean
     */
    public function getPasswordSet()
    {
        return $this->password_set;
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
     * @param text $mail
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
     * Get Professionalexpertise
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
     * @param \MissionBundle\Entity\ProfessionalExpertise $professionalExpertise
     * @return User
     */
    public function addProfessionalExpertise(\MissionBundle\Entity\ProfessionalExpertise $professionalExpertise)
    {
        $this->professionalExpertise[] = $professionalExpertise;

        return $this;
    }

    /**
     * Remove Professionalexpertise
     *
     * @param \MissionBundle\Entity\ProfessionalExpertise $professionalExpertise
     */
    public function removeProfessionalExpertise(\MissionBundle\Entity\ProfessionalExpertise $professionalExpertise)
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
     * Add Missionkind
     *
     * @param \MissionBundle\Entity\MissionKind $missionKind
     * @return User
     */
    public function addMissionKind(\MissionBundle\Entity\MissionKind $missionKind)
    {
        $this->missionKind[] = $missionKind;

        return $this;
    }

    /**
     * Remove MissionKind
     *
     * @param \MissionBundle\Entity\MissionKind $missionKind
     */
    public function removeMissionKind(\MissionBundle\Entity\MissionKind $missionKind)
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
     * Set NbLoad
     *
     * @param integer $nbLoad
     * @return User
     */
    public function setNbLoad($nbLoad = 10)
    {
        $this->nbLoad = $nbLoad;

        return $this;
    }

    /**
     * Get Nbload
     *
     * @return integer
     */
    public function getNbLoad()
    {
        return $this->nbLoad;
    }

    /**
     * Set ReadReport
     *
     * @param boolean $readReport
     * @return User
     */
    public function setReadReport($readReport = true)
    {
        $this->readReport = $readReport;

        return $this;
    }

    /**
     * Get ReadReport
     *
     * @return boolean
     */
    public function getReadReport()
    {
        return $this->readReport;
    }

    /**
     * Get workExperience
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkExperience()
    {
        return $this->workExperience;
    }

    /**
     * Add workExperience
     *
     * @param \MissionBundle\Entity\WorkExperience $workExperience
     * @return User
     */
    public function addWorkExperience(\MissionBundle\Entity\WorkExperience $workExperience)
    {
        $this->workExperience[] = $workExperience;

        return $this;
    }

    /**
     * Reset workExperience
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function resetWorkExperience()
    {
        unset($this->workExperience);
        $this->workExperience = new ArrayCollection();

        return $this->workExperience;
    }

    /**
     * Remove workExperience
     *
     * @param \MissionBundle\Entity\WorkExperience $workExperience
     */
    public function removeWorkExperience(\MissionBundle\Entity\WorkExperience $workExperience)
    {
        $this->workExperience->removeElement($workExperience);
    }

    /**
     * Get ExperienceShaping
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExperienceShaping()
    {
        return $this->experienceShaping;
    }

    /**
     * Add ExperienceShaping
     *
     * @param \MissionBundle\Entity\ExperienceShaping $experienceShaping
     * @return User
     */
    public function addExperienceShaping($experienceShaping)
    {
        $this->experienceShaping[] = $experienceShaping;

        return $this;
    }

    /**
     * Remove ExperienceShaping
     *
     * @param \MissionBundle\Entity\ExperienceShaping $experienceShaping
     */
    public function removeExperienceShaping($experienceShaping)
    {
        $this->experienceShaping->removeElement($experienceShaping);
    }

    /**
     * @Assert\Callback
     */
    public function isValidate(ExecutionContextInterface $context)
    {
        $feesMin = $this->getDailyFeesMin();
        $feesMax = $this->getDailyFeesMax();

        $this->setUpdateDate(new \Datetime());

        if ($this->getPhone() != NULL)
        {
            $this->getPhone()->isValidate($context);
        }
        if ($feesMin == NULL && $feesMax != NULL)
        {
            $context
                ->buildViolation('user.minfees.unset')
                ->atPath('dailyFeesMin')
                ->addViolation();
        }
        else if ($feesMax == NULL && $feesMin != NULL)
        {
            $context
                ->buildViolation('user.maxfees.unset')
                ->atPath('dailyFeesMax')
                ->addViolation();
        }
        else if ($feesMin != NULL && $feesMax != NULL && $feesMin >= $feesMax)
        {
            $context
                ->buildViolation('user.minfees.over')
                ->atPath('dailyFeesMin')
                ->addViolation();
        }
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
     * Get linkedinData
     *
     * @return array 
     */
    public function getLinkedinData()
    {
        return $this->linkedinData;
    }
}
