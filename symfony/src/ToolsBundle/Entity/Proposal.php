<?php

namespace ToolsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proposal
 *
 * @ORM\Table(name="proposal")
 * @ORM\Entity(repositoryClass="ToolsBundle\Repository\ProposalRepository")
 */
class Proposal extends Upload
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="InboxBundle\Entity\Thread",
     *     inversedBy="proposals", cascade={"persist"})
     */
    private $thread;

    /**
     * @ORM\ManyToOne(targetEntity="MissionBundle\Entity\Mission",
     *     inversedBy="proposals", cascade={"persist"})
     */
    private $mission;

    /**
     * @var string
     *
     * @ORM\Column(name="content",
     *     type="string", nullable=true)
     */
    private $content;

    /**
     * UploadResume constructor.
     */
    public function __construct()
    {
        parent::__construct();
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
     * Set thread
     *
     * @param \InboxBundle\Entity\Thread $thread
     * @return Proposal
     */
    public function setThread($thread)
    {
        $thread->addProposal($this);
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \InboxBundle\Entity\Thread
     */
    public function getThread()
    {
        return $this->thread;
    }


    /**
     * Set content
     *
     * @param string $content
     * @return Proposal
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set mission
     *
     * @param \MissionBundle\Entity\Mission $mission
     * @return Proposal
     */
    public function setMission(\MissionBundle\Entity\Mission $mission)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get mission
     *
     * @return \MissionBundle\Entity\Mission 
     */
    public function getMission()
    {
        return $this->mission;
    }
}
