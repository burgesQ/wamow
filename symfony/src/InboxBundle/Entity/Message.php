<?php

namespace InboxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Entity\Message as BaseMessage;

/**
 *  @ORM\Entity(repositoryClass="InboxBundle\Repository\MessageRepository")
 */
class Message extends BaseMessage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="InboxBundle\Entity\Thread",
     *   inversedBy="messages"
     * )
     * @var \FOS\MessageBundle\Model\ThreadInterface
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $sender;

    /**
     * @ORM\OneToMany(
     *   targetEntity="InboxBundle\Entity\MessageMetadata",
     *   mappedBy="message",
     *   cascade={"all"}
     * )
     * @var MessageMetadata[]|Collection
     */
    protected $metadata;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $userMissionProposalId;

    /**
     * Get metadata
     *
     * @return \Doctrine\Common\Collections\Collection|\InboxBundle\Entity\MessageMetadata[]
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set userMissionProposalId
     *
     * @param integer $userMissionProposalId
     * @return Message
     */
    public function setUserMissionProposalId($userMissionProposalId)
    {
        $this->userMissionProposalId = $userMissionProposalId;

        return $this;
    }

    /**
     * Get userMissionProposalId
     *
     * @return integer
     */
    public function getUserMissionProposalId()
    {
        return $this->userMissionProposalId;
    }
}
