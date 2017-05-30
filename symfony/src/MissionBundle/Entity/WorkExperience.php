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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="MissionBundle\Entity\UserWorkExperience",
     *     mappedBy="workExperience"
     * )
     */
    private $userWorkExperiences;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
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
}
