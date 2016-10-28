<?php

namespace ToolsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * PhoneNumber
 *
 * @ORM\Table(name="phone_number")
 * @ORM\Entity(repositoryClass="ToolsBundle\Repository\PhoneNumberRepository")
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
     */
    public function isValidate(ExecutionContext $context)
    {
        $prefix = $this->getPrefix();
        $tel = $this->getTel();

        if  ($tel === NULL && $prefix !== NULL) {
            $context
                ->buildViolation('tools.prefix.phone')
                ->atPath('tel')
                ->addViolation();
        } else if  ($tel !== NULL && $prefix === NULL) {
            $context
                ->buildViolation('tools.phone.prefix')
                ->atPath('prefix')
                ->addViolation();
        }
    }

}
