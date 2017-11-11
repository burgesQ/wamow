<?php

namespace MissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MissionTitle
 *
 * @ORM\Table(name="mission_title")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\MissionTitleRepository")
 */
class MissionTitle
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
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="MissionBundle\Entity\Mission",
     *     cascade={"persist"},
     *     mappedBy="title")
     */
    private $missions;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\WorkExperience",
     *     mappedBy="missionTitles")
     */
    private $workExperiences;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->missions        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workExperiences = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
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
     * Set title
     *
     * @param string $title
     * @return MissionTitle
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return MissionTitle
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Add missions
     *
     * @param \MissionBundle\Entity\Mission $missions
     * @return MissionTitle
     */
    public function addMission(\MissionBundle\Entity\Mission $missions)
    {
        $this->missions[] = $missions;

        return $this;
    }

    /**
     * Remove missions
     *
     * @param \MissionBundle\Entity\Mission $missions
     */
    public function removeMission(\MissionBundle\Entity\Mission $missions)
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
     * Add workExperiences
     *
     * @param \MissionBundle\Entity\WorkExperience $workExperiences
     * @return MissionTitle
     */
    public function addWorkExperience(\MissionBundle\Entity\WorkExperience $workExperiences)
    {
        $this->workExperiences[] = $workExperiences;

        return $this;
    }

    /**
     * Remove workExperiences
     *
     * @param \MissionBundle\Entity\WorkExperience $workExperiences
     */
    public function removeWorkExperience(\MissionBundle\Entity\WorkExperience $workExperiences)
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
