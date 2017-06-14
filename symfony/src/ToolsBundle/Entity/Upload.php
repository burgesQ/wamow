<?php

namespace ToolsBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
    protected $id;

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
     * @var ArrayCollection
     *
     * @ORM\Column(name="format", type="array", nullable=false)
     */
    private $format;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var int
     *
     * @ORM\Column(name="kind", type="smallint", nullable=false)
     */
    private $kind;

    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="download_name", type="string", length=255, nullable=true)
     */
    private $downloadName;

    public function __construct()
    {
        $this->uploadDate = new \Datetime();
        $this->format     = new ArrayCollection();
        $this->kind       = 0;
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
        $this->addFormat($info[1]);
        $this->setName($this->kind.$this->id.time().'.'.$info[1]);
        $this->setPath($this->getUploadRootDir().$this->getName());
        $this->setDownloadName($this->getFile()->getClientOriginalName());
    }

    /**
     * @Assert\Callback
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     */
    public function isValidate($context)
    {
        if ($this->file) {
            $info = explode("/", $this->file->getMimeType());
            if ((!$this->format->isEmpty() && !$this->format->contains($info[1]))
                || ($this->type && $this->type != $info[0]) ) {
                $context
                    ->buildViolation('tools.upload.badFormat')
                    ->atPath('file')
                    ->addViolation();
            }
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null == $this->file) {
            return;
        }
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->name);
    }
    
    public function getUploadDir()
    {
        return 'uploads/';
    }

    public function getUploadRootDir()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir();
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
     * Add format
     *
     * @param string $format
     * @return Upload
     */
    public function addFormat($format)
    {
        $this->format[] = $format;

        return $this;
    }

    /**
     * Remove Format
     *
     * @param string $format
     * @return Upload
     */
    public function removeFormat($format)
    {
        $this->format->removeElement($format);

        return $this;
    }

    /**
     * Get format
     *
     * @return ArrayCollection
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
     * Set kind
     *
     * @param string $kind
     * @return Upload
     */
    public function setKind($kind)
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * Get kind
     *
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }
    
    /**
     * Set file
     *
     * @param UploadedFile $file
     * @return Upload
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set format
     *
     * @param array $format
     * @return Upload
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Set downloadName
     *
     * @param string $downloadName
     * @return Upload
     */
    public function setDownloadName($downloadName)
    {
        $this->downloadName = $downloadName;

        return $this;
    }

    /**
     * Get downloadName
     *
     * @return string 
     */
    public function getDownloadName()
    {
        return $this->downloadName;
    }
}
