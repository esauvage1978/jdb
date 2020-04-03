<?php

namespace App\Listener;

use App\Entity\Picture;
use App\Entity\Project;
use App\Helper\FileDirectory;
use App\Service\Uploader;
use Doctrine\ORM\Mapping as ORM;

class PictureUploadListener
{
    /**
     * @var Uploader
     */
    private $uploader;

    /**
     * @var string
     */
    private $directory;

    public function __construct(Uploader $uploader, string $directory)
    {
        $this->uploader = $uploader;
        $this->directory = $directory;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistHandler(Project $project)
    {
        if (!empty($project->getFile())) {

            $extension = $this->uploader->getExtension($project->getFile());

            if (empty($project->getFileName())) {
                $project->setFileName(md5(uniqid()));
            } else {
                $fileDirectory = new FileDirectory();
                $targetDir = $this->directory;
                $fileDirectory->removeFile($targetDir, $project->getFullName());
            }

            $project->setFileExtension($extension);
        }
        $project->setUpdateAt(new \DateTime());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function postPersistHandler(Project $project)
    {
        if (!empty($project->getFile())) {

            $targetDir = $this->directory;


            $this->uploader->setTargetDir($targetDir);
            $this->uploader->upload($project->getFile(), $project->getFileName());
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function postRemoveHandler(Project $project)
    {
        $fileDirectory = new FileDirectory();
        $targetDir = $this->directory;
        $fileDirectory->removeFile($targetDir, $project->getFullName());
    }
}
