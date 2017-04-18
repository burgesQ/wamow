<?php

namespace InboxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Entity\Thread as BaseThread;

/**
 * @ORM\Entity(repositoryClass="InboxBundle\Repository\ThreadRepository")
 */
class Thread extends BaseThread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(
     *   targetEntity="InboxBundle\Entity\Message",
     *   mappedBy="thread"
     * )
     * @var Message[]|Collection
     */
    protected $messages;

    /**
     * @ORM\OneToMany(
     *   targetEntity="InboxBundle\Entity\ThreadMetadata",
     *   mappedBy="thread",
     *   cascade={"all"}
     * )
     * @var ThreadMetadata[]|Collection
     */
    protected $metadata;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="MissionBundle\Entity\Mission",
     *     inversedBy="threads"
     * )
     */
    private $mission;

    /**
     * @ORM\OneToOne(
     *     targetEntity="MissionBundle\Entity\UserMission"
     * )
     */
    protected $userMission;

    /**
     * @var string
     *
     * @ORM\Column(
     *     name="reply",
     *     type="text",
     *     nullable=true
     * )
     */
    protected $reply;

    public function __construct()
    {
        parent::__construct();

        $this->reply = null;
    }

    /**
     * Get Metadata
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set Metadata
     *
     * @param \Doctrine\Common\Collections\Collection
     *
     * @return Thread
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }
    /**
     * Set Mission
     *
     * @param $mission
     *
     * @return Thread
     */
    public function setMission($mission)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get Mission
     *
     * @return /MissionBundle/Entity/Mission
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Set UserMission
     *
     * @param $userMission
     *
     * @return Thread
     */
    public function setUserMission($userMission)
    {
        $this->userMission = $userMission;

        return $this;
    }

    /**
     * Get UserMission
     *
     * @return \MissionBundle\Entity\UserMission
     */
    public function getUserMission()
    {
        return $this->userMission;
    }

    /**
     * Set Reply
     *
     * @param $reply
     *
     * @return Thread
     */
    public function setReply($reply)
    {
        $this->reply = $reply;

        return $this;
    }

    /**
     * Get Reply
     *
     * @return string
     */
    public function getReply()
    {
        return $this->reply;
    }

}
