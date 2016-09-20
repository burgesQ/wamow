<?php

namespace MissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\ProfessionalExpertise;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @return ProfessionalExpertise
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
}
