<?php

namespace MissionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * WorkExperience
 *
 * @ORM\Table(name="work_experience")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\WorkExperienceRepository")
 */
class WorkExperience
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="MissionBundle\Entity\UserWorkExperience",
     *     mappedBy="workExperience"
     * )
     */
    private $userWorkExperiences;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="MissionBundle\Entity\BusinessPractice",
     *     inversedBy="workExperiences")
     */
    private $businessPractices;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="MissionBundle\Entity\ProfessionalExpertise",
     *     inversedBy="workExperiences")
     */
    private $professionalExpertises;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="MissionBundle\Entity\MissionKind",
     *     inversedBy="workExperiences")
     */
    private $missionKinds;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="MissionBundle\Entity\Mission",
     *     mappedBy="workExperience"
     * )
     */
    private $missions;

    /**
     ** @ORM\ManyToOne(
     *     targetEntity="MissionBundle\Entity\MissionTitle",
     *     inversedBy="workExperiences"
     * )
     */
    private $missionTitle;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users                  = new ArrayCollection();
        $this->businessPractices      = new ArrayCollection();
        $this->professionalExpertises = new ArrayCollection();
        $this->missionKinds           = new ArrayCollection();
        $this->missions               = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return WorkExperience
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add users
     *
     * @param \MissionBundle\Entity\UserWorkExperience $users
     * @return WorkExperience
     */
    public function addUser($users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \MissionBundle\Entity\UserWorkExperience $users
     */
    public function removeUser($users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Add userWorkExperiences
     *
     * @param \MissionBundle\Entity\UserWorkExperience $userWorkExperiences
     * @return WorkExperience
     */
    public function addUserWorkExperience($userWorkExperiences)
    {
        $this->userWorkExperiences[] = $userWorkExperiences;

        return $this;
    }

    /**
     * Remove userWorkExperiences
     *
     * @param \MissionBundle\Entity\UserWorkExperience $userWorkExperiences
     */
    public function removeUserWorkExperience($userWorkExperiences)
    {
        $this->userWorkExperiences->removeElement($userWorkExperiences);
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
     * Add businessPractices
     *
     * @param \MissionBundle\Entity\BusinessPractice $businessPractices
     * @return WorkExperience
     */
    public function addBusinessPractice($businessPractices)
    {
        $businessPractices->addWorkExperience($this);
        $this->businessPractices[] = $businessPractices;

        return $this;
    }

    /**
     * Remove businessPractices
     *
     * @param \MissionBundle\Entity\BusinessPractice $businessPractices
     */
    public function removeBusinessPractice($businessPractices)
    {
        $businessPractices->removeWorkExperience($this);
        $this->businessPractices->removeElement($businessPractices);
    }

    /**
     * Get businessPractices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBusinessPractices()
    {
        return $this->businessPractices;
    }

    /**
     * Add professionalExpertises
     *
     * @param \MissionBundle\Entity\ProfessionalExpertise $professionalExpertises
     * @return WorkExperience
     */
    public function addProfessionalExpertise($professionalExpertises)
    {
        $professionalExpertises->addWorkExperience($this);
        $this->professionalExpertises[] = $professionalExpertises;

        return $this;
    }

    /**
     * Remove professionalExpertises
     *
     * @param \MissionBundle\Entity\ProfessionalExpertise $professionalExpertises
     */
    public function removeProfessionalExpertise($professionalExpertises)
    {
        $professionalExpertises->removeWorkExperience($this);
        $this->professionalExpertises->removeElement($professionalExpertises);
    }

    /**
     * Get professionalExpertises
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProfessionalExpertises()
    {
        return $this->professionalExpertises;
    }

    /**
     * Add missionKinds
     *
     * @param \MissionBundle\Entity\MissionKind $missionKinds
     * @return WorkExperience
     */
    public function addMissionKind($missionKinds)
    {
        $missionKinds->addWorkExperience($this);
        $this->missionKinds[] = $missionKinds;

        return $this;
    }

    /**
     * Remove missionKinds
     *
     * @param \MissionBundle\Entity\MissionKind $missionKinds
     */
    public function removeMissionKind($missionKinds)
    {
        $missionKinds->removeWorkExperience($this);
        $this->missionKinds->removeElement($missionKinds);
    }

    /**
     * Get missionKinds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMissionKinds()
    {
        return $this->missionKinds;
    }

    /**
     * Add missions
     *
     * @param \MissionBundle\Entity\Mission $missions
     * @return WorkExperience
     */
    public function addMission($missions)
    {
        $this->missions[] = $missions;

        return $this;
    }

    /**
     * Remove missions
     *
     * @param \MissionBundle\Entity\Mission $missions
     */
    public function removeMission($missions)
    {
        $this->missions->removeElement($missions);
    }

    /**
     * Get missions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMissions()
    {
        return $this->missions;
    }

    /**
     * Set missionTitle
     *
     * @param \MissionBundle\Entity\MissionTitle $missionTitle
     * @return WorkExperience
     */
    public function setMissionTitle(\MissionBundle\Entity\MissionTitle $missionTitle = null)
    {
        $this->missionTitle = $missionTitle;

        return $this;
    }

    /**
     * Get missionTitle
     *
     * @return \MissionBundle\Entity\MissionTitle 
     */
    public function getMissionTitle()
    {
        return $this->missionTitle;
    }
}
