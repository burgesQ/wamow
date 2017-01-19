<?php

namespace InboxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Entity\Thread as BaseThread;

use MissionBundle\Entity\Mission;

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
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\Mission")
     */
    private $mission;

    /**
     * @ORM\ManyToOne(targetEntity="TeamBundle\Entity\Team")
     */
    protected $teamCreator;

    /**
     * @var reply
     * @ORM\Column(name="reply", type="text", nullable=true)
     */
    protected $reply;

    public function __construct()
    {
        parent::__construct();

        $this->reply = null;
    }
    
    /**
     * Set Mission
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
     * @return MissionBundle\Entity\Mission
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Get metadata
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * set TeamCreator
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setTeamCreator($teamCreator)
    {
        $this->teamCreator = $teamCreator;

        return $this;
    }

    /**
     * Get TeamCreator
     *
     * @return TeamBundle\Entity\Team
     */
    public function getTeamCreator()
    {
        return $this->teamCreator;
    }

    /**
     * set Reply
     *
     * @return \Doctrine\Common\Collections\Collection
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
