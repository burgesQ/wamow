<?php

namespace MissionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProfessionalExpertise
 *
 * @ORM\Table(name="professional_expertise")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\ProfessionalExpertiseRepository")
 */
class ProfessionalExpertise
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
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="MissionBundle\Entity\WorkExperience",
     *     mappedBy="contractorProfessionalExpertises")
     */
    private $contractorWorkExperiences;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="MissionBundle\Entity\WorkExperience",
     *     mappedBy="advisorProfessionalExpertises")
     */
    private $advisorWorkExperiences;

    /**
     * ProfessionalExpertise constructor.
     */
    public function __construct()
    {
        $this->contractorWorkExperiences = new ArrayCollection();
        $this->advisorWorkExperiences    = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
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
     * @return ProfessionalExpertise
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Add contractorWorkExperiences
     *
     * @param \MissionBundle\Entity\WorkExperience $contractorWorkExperiences
     * @return ProfessionalExpertise
     */
    public function addContractorWorkExperience($contractorWorkExperiences)
    {
        $this->contractorWorkExperiences[] = $contractorWorkExperiences;

        return $this;
    }

    /**
     * Remove contractorWorkExperiences
     *
     * @param \MissionBundle\Entity\WorkExperience $contractorWorkExperiences
     */
    public function removeContractorWorkExperience($contractorWorkExperiences)
    {
        $this->contractorWorkExperiences->removeElement($contractorWorkExperiences);
    }

    /**
     * Get contractorWorkExperiences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContractorWorkExperiences()
    {
        return $this->contractorWorkExperiences;
    }

    /**
     * Add advisorWorkExperiences
     *
     * @param \MissionBundle\Entity\WorkExperience $advisorWorkExperiences
     * @return ProfessionalExpertise
     */
    public function addAdvisorWorkExperience($advisorWorkExperiences)
    {
        $this->advisorWorkExperiences[] = $advisorWorkExperiences;

        return $this;
    }

    /**
     * Remove advisorWorkExperiences
     *
     * @param \MissionBundle\Entity\WorkExperience $advisorWorkExperiences
     */
    public function removeAdvisorWorkExperience($advisorWorkExperiences)
    {
        $this->advisorWorkExperiences->removeElement($advisorWorkExperiences);
    }

    /**
     * Get advisorWorkExperiences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdvisorWorkExperiences()
    {
        return $this->advisorWorkExperiences;
    }
}
