<?php

namespace MissionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\OneToMany(
     *     targetEntity="MissionBundle\Entity\ExperienceShaping",
     *     mappedBy="workExperience"
     * )
     */
    private $experienceShaping;

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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->experienceShaping = new ArrayCollection();
    }

    /**
     * Add experienceShaping
     *
     * @param \MissionBundle\Entity\ExperienceShaping $experienceShaping
     * @return WorkExperience
     */
    public function addExperienceShaping($experienceShaping)
    {
        $this->experienceShaping[] = $experienceShaping;

        return $this;
    }

    /**
     * Remove experienceShaping
     *
     * @param \MissionBundle\Entity\ExperienceShaping $experienceShaping
     */
    public function removeExperienceShaping($experienceShaping)
    {
        $this->experienceShaping->removeElement($experienceShaping);
    }

    /**
     * Get experienceShaping
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExperienceShaping()
    {
        return $this->experienceShaping;
    }
}
