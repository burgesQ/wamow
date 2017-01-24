<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use FOS\UserBundle\Model\User as BaseUser;
use CompanyBundle\Entity\Company;
use CalendarBundle\Entity\Calendar;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
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
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebook_id;

    /**
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebook_access_token;

    /**
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     */
    protected $google_id;

    /**
     * @ORM\Column(name="google_access_token", type="string", length=255, nullable=true)
     */
    protected $google_access_token;

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
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
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
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
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
     * @ORM\OneToMany(targetEntity="ToolsBundle\Entity\UploadResume", mappedBy="user")
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
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\UserData", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $userData;
        
    /**
     * @ORM\OneToOne(targetEntity="CalendarBundle\Entity\Calendar", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $calendar;

    public function __construct()
    {
        parent::__construct();
        $this->creationDate = new \Datetime();
        $this->confidentiality = false;
        $this->status = 0;
        $this->prefix = NULL;
        $this->birthdate = NULL;
        $this->images = new ArrayCollection();
        $this->resumes = new ArrayCollection();
        $this->newsletter = true;
        $this->giveUpCount = 0;
        $this->secretMail = array();
        $this->userData = NULL;
        $this->userResume = NULL;
        $this->languages = new ArrayCollection();
        $this->professionalexpertise = new ArrayCollection();
        $this->missionkind = new ArrayCollection();
        $this->businessPractice = new ArrayCollection();
        $this->calendar = new Calendar();
        $this->company = NULL;
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @param \ToolsBundle\Entity\Upload $images
     * @return User
     */
    public function addImage(\ToolsBundle\Entity\Upload $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \ToolsBundle\Entity\Upload $images
     */
    public function removeImage(\ToolsBundle\Entity\Upload $images)
    {
        $this->images->removeElement($images);
    }


    /**
     * Get images
     *
     * @return \ToolsBundle\Entity\Upload
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
     * Set facebook_id
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Set google_id
     *
     * @param string $googleId
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->google_id = $googleId;

        return $this;
    }

    /**
     * Get google_id
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Set google_access_token
     *
     * @param string $googleAccessToken
     * @return User
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->google_access_token = $googleAccessToken;

        return $this;
    }

    /**
     * Get google_access_token
     *
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->google_access_token;
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
     * @param text $mails
     * @return User
     */
    public function addSecretMail($mail)
    {
        array_push( $this->secretMail, $mail);
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
     * Add resume
     *
     * @param \ToolsBundle\Entity\UploadResume $resume
     * @return User
     */
    public function addResumes(\ToolsBundle\Entity\UploadResume $resume = null)
    {
        $this->resumes[] = $resume;

        return $this;
    }

    /**
     * Get resumes
     *
     * @return \ToolsBundle\Entity\UploadResume
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
     * Add languages
     *
     * @param \ToolsBundle\Entity\Language $languages
     * @return User
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
    public function addBusinessPractice(\MissionBundle\Entity\BusinessPractice $businessPractice)
    {
        $this->businesspractic[] = $businessPractice;

        return $this;
    }

    /**
     * Remove BusinessPractice
     *
     * @param \MissionBundle\Entity\BusinessPractice $businessPractice
     */
    public function removeBusinessPractice(\MissionBundle\Entity\BusinessPractice $businessPractice)
    {
        $this->businessPractice->removeElement($businessPractice);
    }

    /**
     * Set UserData
     *
     * @param \UserBundle\Entity\UserData $data
     * @return User
     */
    public function setUserData(\UserBundle\Entity\UserData $data = null)
    {
        $this->userdata = $data;

        return $this;
    }

    /**
     * Get UserData
     *
     * @return \UserBundle\Entity\UserData
     */
    public function getUserData()
    {
        return $this->userData;
    }   
    
    /**
     * @Assert\Callback
     */
    public function isValidate(ExecutionContextInterface $context)
    {
        $feesMin = $this->getDailyFeesMin();
        $feesMax = $this->getDailyFeesMax();

        $this->setUpdateDate(new \Datetime());

        if ($this->getPhone() != NULL) {
            $this->getPhone()->isValidate($context);
        } if ($feesMin == NULL && $feesMax != NULL) {
            $context
                ->buildViolation('user.minfees.unset')
                ->atPath('dailyFeesMin')
                ->addViolation();
        } else if ($feesMax == NULL && $feesMin != NULL) {
            $context
                ->buildViolation('user.maxfees.unset')
                ->atPath('dailyFeesMax')
                ->addViolation();
        } else if ($feesMin != NULL && $feesMax != NULL && $feesMin >= $feesMax) {
            $context
                ->buildViolation('user.minfees.over')
                ->atPath('dailyFeesMin')
                ->addViolation();
        }
    }

    /**
     * Set calendar
     *
     * @param \CalendarBundle\Entity\Calendar $calendar
     *
     * @return User
     */
    public function setCalendar(\CalendarBundle\Entity\Calendar $calendar)
    {
        $this->calendar = $calendar;

        return $this;
    }

    /**
     * Get calendar
     *
     * @return \CalendarBundle\Entity\Calendar
     */
    public function getCalendar()
    {
        return $this->calendar;
    }
}
