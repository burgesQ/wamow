<?php

namespace MissionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * companySize
 *
 * @ORM\Table(name="company_size")
 * @ORM\Entity(repositoryClass="MissionBundle\Repository\companySizeRepository")
 */
class CompanySize
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
    * @ORM\ManyToMany(
    *     targetEntity="MissionBundle\Entity\UserWorkExperience",
    *     mappedBy="companySizes"
    * )
    */
   private $userWorkExperiences;

    /**
     * Continent constructor.
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->name                = $name;
        $this->userWorkExperiences = new ArrayCollection();
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
     * @return companySize
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Add userWorkExperiences
     *
     * @param \MissionBundle\Entity\UserWorkExperience $userWorkExperiences
     * @return CompanySize
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
