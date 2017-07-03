<?php

namespace MissionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * MissionKind
 *
 * @ORM\Table(name="mission_kind")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\MissionKindRepository")
 */
class MissionKind
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
     *     mappedBy="missionKinds")
     */
    private $workExperiences;

    /**
     * MissionKind constructor.
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
     * @return MissionKind
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
     * @return MissionKind
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
