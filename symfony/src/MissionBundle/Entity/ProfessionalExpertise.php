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
     *     mappedBy="professionalExpertises")
     */
    private $workExperiences;

    /**
     * ProfessionalExpertise constructor.
     */
    public function __construct()
    {
        $this->workExperiences = new ArrayCollection();
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
     * Add workExperiences
     *
     * @param \MissionBundle\Entity\WorkExperience $workExperiences
     * @return ProfessionalExpertise
     */
    public function addWorkExperience($workExperiences)
    {
        $this->workExperiences[] = $workExperiences;

        return $this;
    }

    /**
     * Remove workExperiences
     *
     * @param \MissionBundle\Entity\WorkExperience $workExperiences
     */
    public function removeWorkExperience($workExperiences)
    {
        $this->workExperiences->removeElement($workExperiences);
    }

    /**
     * Get workExperiences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkExperiences()
    {
        return $this->workExperiences;
    }
}
