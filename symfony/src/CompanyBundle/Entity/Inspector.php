<?php

namespace CompanyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Inspector
 *
 * @ORM\Table(name="inspector")
 * @ORM\Entity(repositoryClass="CompanyBundle\Repository\InspectorRepository")
 */
class Inspector
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
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\Mission", cascade={"persist"}, mappedBy="inspectors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $missions;

    /**
     * Constructor
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->name     = $name;
        $this->missions = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Inspector
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
     * Add missions
     *
     * @param \MissionBundle\Entity\Mission $missions
     * @return Inspector
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
}
