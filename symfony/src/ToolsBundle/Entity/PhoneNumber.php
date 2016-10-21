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
 * @Assert\Callback(methods={"isValidate"})
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
     *  message="The phone number can only have between 4 and 10 digits."
     * )
     * @ORM\Column(name="tel", type="string", length=255)
     */
    private $tel;

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
     * Set tel
     *
     * @param string $tel
     * @return PhoneNumber
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
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
                ->buildViolation("You must enter a phone number with the prefix.")
                ->atPath('tel')
                ->addViolation();
        } else if  ($tel !== NULL && $prefix === NULL) {
            $context
                ->buildViolation("You must enter a prefix with the phone number.")
                ->atPath('prefix')
                ->addViolation();
        }
    }

}
