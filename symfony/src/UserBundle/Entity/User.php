<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use FOS\UserBundle\Model\User as BaseUser;
use CompanyBundle\Entity\Company;
use ToolsBundle\Entity\PhoneNumber;
use ToolsBundle\Entity\Address;
use ToolsBundle\Entity\Upload;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
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
     *     groups={"Registration", "Profile"}
     * )
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
     *     groups={"Registration", "Profile"}
     * )
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
     * @ORM\Column(name="update_date", type="datetime", nullable=false)
     */
    private $updateDate;

    /**
     * @ORM\OneToOne(targetEntity="ToolsBundle\Entity\Address", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $address;

    /**
     * @ORM\OneToOne(targetEntity="ToolsBundle\Entity\PhoneNumber", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="ToolsBundle\Entity\Upload", mappedBy="user")
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="ToolsBundle\Entity\Upload", mappedBy="user")
     */
    private $resumes;

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

    public function __construct()
    {
        parent::__construct();
        $this->creationDate = new \Datetime();
        $this->updateDate = new \Datetime();
        $this->confidentiality = false;
        $this->status = 0;
        $this->address = NULL;
        $this->prefix = NULL;
        $this->birthdate = NULL;
        $this->images = new ArrayCollection();
        $this->resumes = new ArrayCollection();
        $this->newsletter = true;
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
     * Set address
     *
     * @param \ToolsBundle\Entity\Address $address
     * @return User
     */
    public function setAddress(\ToolsBundle\Entity\Address $address = null)
    {
        $this->address = $address;

        return $this;
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
     *  Add image
     *
     * @param \ToolsBundle\Entity\Upload $image
     * @return User
     */
    public function addImages(\ToolsBundle\Entity\Upload $image = null)
    {
        $this->images[] = $image;

        return $this;
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
     * Add resumes
     *
     * @param \ToolsBundle\Entity\Upload $resume
     * @return User
     */
    public function addResumes(\ToolsBundle\Entity\Upload $resume = null)
    {
        $this->resumes[] = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return \ToolsBundle\Entity\Upload
     */
    public function getResumes()
    {
        return $this->resumes;
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
     * @Assert\Callback
     */
    public function isValidate(ExecutionContextInterface $context)
    {
        $feesMin = $this->getDailyFeesMin();
        $feesMax = $this->getDailyFeesMax();

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
     * Add team
     *
     * @param \TeamBundle\Entity\Team $team
     * @return User
     */
    public function addTeam(\TeamBundle\Entity\Team $team)
    {
        $this->team[] = $team;

        return $this;
    }

    /**
     * Remove team
     *
     * @param \TeamBundle\Entity\Team $team
     */
    public function removeTeam(\TeamBundle\Entity\Team $team)
    {
        $this->team->removeElement($team);
    }

    /**
     * Get team
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeam()
    {
        return $this->team;
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
     * Add resumes
     *
     * @param \ToolsBundle\Entity\Upload $resumes
     * @return User
     */
    public function addResume(\ToolsBundle\Entity\Upload $resumes)
    {
        $this->resumes[] = $resumes;

        return $this;
    }

    /**
     * Remove resumes
     *
     * @param \ToolsBundle\Entity\Upload $resumes
     */
    public function removeResume(\ToolsBundle\Entity\Upload $resumes)
    {
        $this->resumes->removeElement($resumes);
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
}
