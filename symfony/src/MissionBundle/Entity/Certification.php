<?php

namespace MissionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Certification
 *
 * @ORM\Table(name="certification")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\CertificationRepository")
 */
class Certification
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
     * @ORM\ManyToMany(targetEntity="MissionBundle\Entity\Mission", cascade={"persist"}, mappedBy="certifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $missions;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User", cascade={"persist"}, mappedBy="certifications")
     * @ORM\JoinColumn(nullable=true)
     */
    private $users;

    /**
     * Constructor
     *
     * @param null $name
     */
    public function __construct($name = null)
    {
        $this->name     = $name;
        $this->missions = new ArrayCollection();
        $this->users    = new ArrayCollection();
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
     * @return Certification
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Add missions
     *
     * @param \MissionBundle\Entity\Mission $missions
     * @return Certification
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
     * Add users
     *
     * @param \UserBundle\Entity\User $users
     * @return Certification
     */
    public function addUser($users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \UserBundle\Entity\User $users
     */
    public function removeUser($users)
    {
        $this->users->removeElement($users);
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
}
