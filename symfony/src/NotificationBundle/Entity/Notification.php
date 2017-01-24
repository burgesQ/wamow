<?php

namespace NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="NotificationBundle\Repository\NotificationRepository")
 */
class Notification
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
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var bool
     *
     * @ORM\Column(name="seen", type="boolean")
     */
    private $seen;

    /**
     * @var string
     *
     * @ORM\Column(name="message_id", type="string", length=255, nullable=false)
     */
    private $messageId;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", cascade={"persist"})
     */
    private $user;

    /**
     * @var array
     *
     * @ORM\Column(name="message_params", type="array", nullable=true)
     */
    private $messageParams;

    public function __construct($user, $status, $messageId, $param)
    {
        $this->messageId = $messageId;
        $this->user = $user;
        $this->status = $status;
        $this->seen = false;
        $this->creationDate = new \Datetime();
        $this->messageParams = $param;
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Notification
     */
    public function setCreationDate($creationDate)
    {
        $this->creationdate = $creationDate;

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
     * Set status
     *
     * @param integer $status
     * @return Notification
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set seen
     *
     * @param boolean $seen
     * @return Notification
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return boolean
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set messageId
     *
     * @param string $messageId
     * @return Notification
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * Get messageId
     *
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return Notification
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set messageParams
     *
     * @param array $messageParams
     * @return Notification
     */
    public function setMessageParams($messageParams)
    {
        $this->messageParams = $messageParams;

        return $this;
    }

    /**
     * Get messageParams
     *
     * @return array
     */
    public function getMessageParams()
    {
        return $this->messageParams;
    }
}
