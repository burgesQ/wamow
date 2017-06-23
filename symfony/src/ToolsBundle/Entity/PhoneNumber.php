<?php

namespace ToolsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * PhoneNumber
 *
 * @ORM\Table(name="phone_number")
 * @ORM\Entity(repositoryClass="ToolsBundle\Repository\PhoneNumberRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PhoneNumber
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
     * @Assert\Regex(
     *  pattern="/^[0-9]{4,10}$/",
     *  message="tools.phone.digits"
     * )
     * @ORM\Column(name="number", type="string", length=255)
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="ToolsBundle\Entity\PrefixNumber", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $prefix;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate;

    public function __toString()
    {
        return $this->number;
    }

    public function __construct()
    {
        $this->creationDate = new \Datetime();
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
     * Set number
     *
     * @param string $number
     * @return PhoneNumber
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set prefix
     *
     * @param \ToolsBundle\Entity\PrefixNumber $prefix
     * @return PhoneNumber
     */
    public function setPrefix(\ToolsBundle\Entity\PrefixNumber $prefix = null)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix
     *
     * @return \ToolsBundle\Entity\PrefixNumber
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     */
    public function isValidate(ExecutionContextInterface $context)
    {
        if  ($this->number === NULL && $this->prefix !== NULL) {
            $context
                ->buildViolation('tools.prefix.phone')
                ->atPath('number')
                ->addViolation();
        } else if  ($this->number !== NULL && $this->prefix === NULL) {
            $context
                ->buildViolation('tools.phone.prefix')
                ->atPath('prefix')
                ->addViolation();
        }
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return PhoneNumber
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return PhoneNumber
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdateDate(new \Datetime());
    }
}
