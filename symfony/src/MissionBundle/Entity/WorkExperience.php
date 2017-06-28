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
     *     inversedBy="contractorWorkExperiences")
     * @JoinTable(name="work_experience_contractor_business_practices")
     */
    private $contractorBusinessPractices;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="MissionBundle\Entity\ProfessionalExpertise",
     *     inversedBy="contractorWorkExperiences")
     * @JoinTable(name="work_experience_contractor_professional_expertises")
     */
    private $contractorProfessionalExpertises;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="MissionBundle\Entity\MissionKind",
     *     inversedBy="contractorWorkExperiences")
     * @JoinTable(name="work_experience_contractor_mission_kinds")
     */
    private $contractorMissionKinds;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="MissionBundle\Entity\BusinessPractice",
     *     inversedBy="advisorWorkExperiences")
     * @JoinTable(name="work_experience_advisor_business_practices")
     */
    private $advisorBusinessPractices;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="MissionBundle\Entity\ProfessionalExpertise",
     *     inversedBy="advisorWorkExperiences")
     * @JoinTable(name="work_experience_advisor_professional_expertises")
     */
    private $advisorProfessionalExpertises;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="MissionBundle\Entity\MissionKind",
     *     inversedBy="advisorWorkExperiences")
     * @JoinTable(name="work_experience_advisor_mission_kinds")
     */
    private $advisorMissionKinds;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users                            = new ArrayCollection();
        $this->contracotrBusinessPractices      = new ArrayCollection();
        $this->contractorProfessionalExpertises = new ArrayCollection();
        $this->contractorMissionKinds           = new ArrayCollection();
        $this->advisorBusinessPractices         = new ArrayCollection();
        $this->advisorProfessionalExpertises    = new ArrayCollection();
        $this->advisorMissionKinds              = new ArrayCollection();
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
     * Add contractorBusinessPractices
     *
     * @param \MissionBundle\Entity\BusinessPractice $contractorBusinessPractices
     * @return WorkExperience
     */
    public function addContractorBusinessPractice($contractorBusinessPractices)
    {
        $contractorBusinessPractices->addContractorWorkExperience($this);
        $this->contractorBusinessPractices[] = $contractorBusinessPractices;

        return $this;
    }

    /**
     * Remove contractorBusinessPractices
     *
     * @param \MissionBundle\Entity\BusinessPractice $contractorBusinessPractices
     */
    public function removeContractorBusinessPractice($contractorBusinessPractices)
    {
        $contractorBusinessPractices->removeContractorWorkExperience($this);
        $this->contractorBusinessPractices->removeElement($contractorBusinessPractices);
    }

    /**
     * Get contractorBusinessPractices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContractorBusinessPractices()
    {
        return $this->contractorBusinessPractices;
    }

    /**
     * Add contractorProfessionalExpertises
     *
     * @param \MissionBundle\Entity\ProfessionalExpertise $contractorProfessionalExpertises
     * @return WorkExperience
     */
    public function addContractorProfessionalExpertise($contractorProfessionalExpertises)
    {
        $contractorProfessionalExpertises->addContractorWorkExperience($this);
        $this->contractorProfessionalExpertises[] = $contractorProfessionalExpertises;

        return $this;
    }

    /**
     * Remove contractorProfessionalExpertises
     *
     * @param \MissionBundle\Entity\ProfessionalExpertise $contractorProfessionalExpertises
     */
    public function removeContractorProfessionalExpertise($contractorProfessionalExpertises)
    {
        $contractorProfessionalExpertises->removeContractorWorkExperience($this);
        $this->contractorProfessionalExpertises->removeElement($contractorProfessionalExpertises);
    }

    /**
     * Get contractorProfessionalExpertises
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContractorProfessionalExpertises()
    {
        return $this->contractorProfessionalExpertises;
    }

    /**
     * Add contractorMissionKinds
     *
     * @param \MissionBundle\Entity\MissionKind $contractorMissionKinds
     * @return WorkExperience
     */
    public function addContractorMissionKind($contractorMissionKinds)
    {
        $contractorMissionKinds->addContractorWorkExperience($this);
        $this->contractorMissionKinds[] = $contractorMissionKinds;

        return $this;
    }

    /**
     * Remove contractorMissionKinds
     *
     * @param \MissionBundle\Entity\MissionKind $contractorMissionKinds
     */
    public function removeContractorMissionKind($contractorMissionKinds)
    {
        $contractorMissionKinds->removeContractorWorkExperience($this);
        $this->contractorMissionKinds->removeElement($contractorMissionKinds);
    }

    /**
     * Get contractorMissionKinds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContractorMissionKinds()
    {
        return $this->contractorMissionKinds;
    }

    /**
     * Add advisorBusinessPractices
     *
     * @param \MissionBundle\Entity\BusinessPractice $advisorBusinessPractices
     * @return WorkExperience
     */
    public function addAdvisorBusinessPractice($advisorBusinessPractices)
    {
        $advisorBusinessPractices->addAdvisorWorkExperience($this);
        $this->advisorBusinessPractices[] = $advisorBusinessPractices;

        return $this;
    }

    /**
     * Remove advisorBusinessPractices
     *
     * @param \MissionBundle\Entity\BusinessPractice $advisorBusinessPractices
     */
    public function removeAdvisorBusinessPractice($advisorBusinessPractices)
    {
        $advisorBusinessPractices->removeAdvisorWorkExperience($this);
        $this->advisorBusinessPractices->removeElement($advisorBusinessPractices);
    }

    /**
     * Get advisorBusinessPractices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdvisorBusinessPractices()
    {
        return $this->advisorBusinessPractices;
    }

    /**
     * Add advisorProfessionalExpertises
     *
     * @param \MissionBundle\Entity\ProfessionalExpertise $advisorProfessionalExpertises
     * @return WorkExperience
     */
    public function addAdvisorProfessionalExpertise($advisorProfessionalExpertises)
    {
        $advisorProfessionalExpertises->addAdvisorWorkExperience($this);
        $this->advisorProfessionalExpertises[] = $advisorProfessionalExpertises;

        return $this;
    }

    /**
     * Remove advisorProfessionalExpertises
     *
     * @param \MissionBundle\Entity\ProfessionalExpertise $advisorProfessionalExpertises
     */
    public function removeAdvisorProfessionalExpertise($advisorProfessionalExpertises)
    {
        $advisorProfessionalExpertises->removeAdvisorWorkExperience($this);
        $this->advisorProfessionalExpertises->removeElement($advisorProfessionalExpertises);
    }

    /**
     * Get advisorProfessionalExpertises
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdvisorProfessionalExpertises()
    {
        return $this->advisorProfessionalExpertises;
    }

    /**
     * Add advisorMissionKinds
     *
     * @param \MissionBundle\Entity\MissionKind $advisorMissionKinds
     * @return WorkExperience
     */
    public function addAdvisorMissionKind($advisorMissionKinds)
    {
        $advisorMissionKinds->addAdvisorWorkExperience($this);
        $this->advisorMissionKinds[] = $advisorMissionKinds;

        return $this;
    }

    /**
     * Remove advisorMissionKinds
     *
     * @param \MissionBundle\Entity\MissionKind $advisorMissionKinds
     */
    public function removeAdvisorMissionKind($advisorMissionKinds)
    {
        $advisorMissionKinds->removeAdvisorWorkExperience($this);
        $this->advisorMissionKinds->removeElement($advisorMissionKinds);
    }

    /**
     * Get advisorMissionKinds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdvisorMissionKinds()
    {
        return $this->advisorMissionKinds;
    }
}
