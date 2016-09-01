<?php

namespace MissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;



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
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\Regex(
     *     pattern="#[^a-zA-Zéèêëçîïíàáâ-]#",
     *     match=false,
     *     message="The country must contain only letters or dash.")
     */
    private $country;

    /**
     * @var int
     *
     * @ORM\Column(name="min_number_user", type="smallint")
     * @Assert\Range(
     *      min = 1,
     *      max = 15,
     *      minMessage = "You must be at least {{ limit }}",
     *      maxMessage = "You cannot be more than {{ limit }}"
     * )
     */
    private $minNumberUser;

    /**
     * @var int
     *
     * @ORM\Column(name="max_number_user", type="smallint", nullable=true)
     * @Assert\Range(
     *      min = 1,
     *      max = 15,
     *      minMessage = "You must be at least {{ limit }}",
     *      maxMessage = "You cannot be more than {{ limit }}"
     * )
     */
    private $maxNumberUser;

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
     * @ORM\Column(name="status", type="smallint", nullable=true)
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
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\Language", cascade={"persist"})
     */
    private $language;

    /**
     * @var bool
     *
     * @ORM\Column(name="telecommuting", type="boolean")
     */
    private $telecommuting;

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
     * @var int
     *
     * @ORM\Column(name="duration", type="integer")
     * @Assert\Range(
     *      min = 2,
     *      max = 9999,
     *      minMessage = "You need to fill this section",)
     */
    private $duration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="beginning", type="datetime")
     */
    private $beginning;

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
     * @ORM\Column(name="zipcode", type="integer")
     * @Assert\Range(
     *      min = 100,
     *      max = 99999,)
     */
    private $zipcode;

    public function __construct()
      {
        $this->creationDate = new \Datetime();
        $this->language = new ArrayCollection();
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
     * Set minNumberUser
     *
     * @param integer $minNumberUser
     * @return Mission
     */
    public function setMinNumberUser($minNumberUser)
    {
        $this->minNumberUser = $minNumberUser;

        return $this;
    }

    /**
     * Get minNumberUser
     *
     * @return integer
     */
    public function getMinNumberUser()
    {
        return $this->minNumberUser;
    }

    /**
     * Set maxNumberUser
     *
     * @param integer $maxNumberUser
     * @return Mission
     */
    public function setMaxNumberUser($maxNumberUser)
    {
        $this->maxNumberUser = $maxNumberUser;

        return $this;
    }

    /**
     * Get maxNumberUser
     *
     * @return integer
     */
    public function getMaxNumberUser()
    {
        return $this->maxNumberUser;
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
    public function setIDCompagny($iDCompany)
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
     * Set language
     *
     * @param array $language
     * @return Mission
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    public function addLanguage(Category $language)
     {
       $this->categories[] = $language;

       return $this;
     }

     public function removeLanguage(Category $language)
     {
       $this->categories->removeElement($language);
     }

    /**
     * Get language
     *
     * @return array
     */
    public function getLanguage()
    {
        return $this->language;
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
     * @return int
     */
    public function getDailyFeesMax()
    {
        return $this->dailyFeesMax;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Mission
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
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
     * @param integer $zipcode
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
     * @return integer
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }
}
