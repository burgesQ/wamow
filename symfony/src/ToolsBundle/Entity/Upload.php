<?php

namespace ToolsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use UserBundle\Entity\User;

/**
 * Upload
 *
 * @ORM\Table(name="upload")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ToolsBundle\Repository\UploadRepository")
 */
class Upload
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
     * @ORM\Column(name="uploadDate", type="datetime", nullable=false)
     */
    private $uploadDate;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="format", type="string", length=255, nullable=true)
     */
    private $format;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    private $file;

    private $laTempo;
    
    public function __construct()
    {
        $this->user = NULL;
        $this->uploadDate = new \Datetime();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (NULL == $this->file) {
            return;
        }
        $info = explode("/", $this->getFile()->getMimeType());
        $this->setType($info[0]);
        $this->setFormat($info[1]);
        $this->setName($this->id.time().'.'.$info[1]);
        $this->setPath($this->getUploadRootDir().$this->getName());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null == $this->file) {
            return;
        } if (NULL != $this->laTempo) {
            $tmp = $this->getUploadRootDir().$this->laTempo;
            if (file_exists($tmp)) {
                rename($tmp, $this->getRmUploadRootDir().$this->laTempo);
            }
        }
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->name);
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->laTempo = $this->getUploadRootDir().$this->getName();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {}
    
    public function getUploadDir()
    {
        return 'uploads/';
    }

    public function getRmUploadDir()
    {
        return 'rm_uploads/';
    }

    public function getUploadRootDir()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

        public function getRmUploadRootDir()
    {
        return __DIR__.'/../../../web/'.$this->getRmUploadDir();
    }

    public function getWebPath()
    {
        return $this->getUploadDir().$this->getName();
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
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return Upload
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
     * Set uploadDate
     *
     * @param \DateTime $uploadDate
     * @return Upload
     */
    public function setUploadDate($uploadDate)
    {
        $this->uploadDate = $uploadDate;

        return $this;
    }

    /**
     * Get uploadDate
     *
     * @return \DateTime
     */
    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Upload
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Upload
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set format
     *
     * @param string $format
     * @return Upload
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Upload
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return Upload
     */
    public function setFile($file)
    {
        $this->file = $file;
        if (null != $this->name) {
            $this->laTempo = $this->name;
            $this->name = null;
            $this->format = null;
            $this->path = null;
            $this->type = null;
        }
        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
}
