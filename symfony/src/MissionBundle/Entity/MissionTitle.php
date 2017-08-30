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
     * @return string
     */
    public function __toString()
    {
     return $this->getTitle();
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
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
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
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->missions = new \Doctrine\Common\Collections\ArrayCollection();
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
}
