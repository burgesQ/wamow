<?php

namespace ToolsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReadResume
 *
 * @ORM\Table(name="readResume")
 * @ORM\Entity(repositoryClass="ToolsBundle\Repository\ReadResumeRepository")
 */
class ReadResume
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
     * @var text
     *
     * @ORM\Column(name="resumeParsed", type="text")
     * )
     */
    private $resumesParsed;

    /**
     * @var int
     *
     * @ORM\Column(name="lastParsed", type="integer")
     * )
     */
    private $lastParsed;
        
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
     * Set resumesParsed
     *
     * @param text $resumesParsed
     * @return ReadResume
     */
    public function setResumesParsed($resumesParsed)
    {
        $this->resumesParsed = $resumesParsed;
        return $this;
    }

    /**
     * Get resumesParsed
     *
     * @return text
     */
    public function getResumesParsed()
    {
        return $this->resumesParsed;
    }

    /**
     * Set lastParsed
     *
     * @param int $lastParsed
     * @return ReadResume
     */
    public function setLastParsed($lastParsed)
    {
        $this->lastParsed = $lastParsed;
        return $this;
    }

    /**
     * Get lastParsed
     *
     * @return int
     */
    public function getLastParsed()
    {
        return $this->lastParsed;
    }    
}

