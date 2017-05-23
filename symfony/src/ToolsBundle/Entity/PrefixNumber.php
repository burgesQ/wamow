<?php

namespace ToolsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrefixNumber
 *
 * @ORM\Table(name="prefix_number")
 * @ORM\Entity(repositoryClass="ToolsBundle\Repository\PrefixNumberRepository")
 */
class PrefixNumber
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
     * @ORM\Column(name="prefix", type="string", length=255)
     */
    private $prefix;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

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
     * Set prefix
     *
     * @param string $prefix
     * @return PrefixNumber
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return PrefixNumber
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }


    /**
     * Get country and prefix for form
     *
     * @return string
     */
    public function getCountryAndPrefix()
    {
        return $this->country . ' (' . $this->prefix . ')';
    }
}
