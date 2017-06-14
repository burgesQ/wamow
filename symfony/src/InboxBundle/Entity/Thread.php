<?php

namespace InboxBundle\Entity;

use FOS\MessageBundle\Entity\Thread as BaseThread;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     *     targetEntity="MissionBundle\Entity\UserMission",
     *     inversedBy="thread"
     * )
     */
    protected $userMission;

    /**
     * @ORM\OneToMany(
     *      targetEntity="ToolsBundle\Entity\Proposal",
     *      mappedBy="thread"
     * )
     * @ORM\JoinColumn(nullable=true)
     */
    private $proposals;

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
        $this->proposals = new ArrayCollection();
        $this->reply     = null;
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
     * @param \MissionBundle\Entity\Mission $mission
     *
     * @return Thread
     */
    public function setMission($mission)
    {
        $mission->addThread($this);
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
     * @param \MissionBundle\Entity\UserMission $userMission
     *
     * @return Thread
     */
    public function setUserMission($userMission)
    {
        $userMission->setThread($this);
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

    /**
     * Add proposal
     *
     * @param \ToolsBundle\Entity\Proposal $proposal
     * @return Thread
     */
    public function addProposal($proposal)
    {
        $this->proposals[] = $proposal;

        return $this;
    }

    /**
     * Remove proposal
     *
     * @param \ToolsBundle\Entity\Upload $proposal
     */
    public function removeProposal($proposal)
    {
        $this->proposals->removeElement($proposal);
    }


    /**
     * Get proposals
     *
     * @return ArrayCollection
     */
    public function getProposals()
    {
        return $this->proposals;
    }
}
