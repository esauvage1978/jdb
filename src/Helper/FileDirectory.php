<?php


namespace App\Helper;


use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class FileDirectory
{
    /**
     * @var Filesystem
     */
    private $fsObject;

    public function __construct()
    {
        $this->fsObject = new Filesystem();
    }

    public function createDir(string $chemin, string $directory)
    {

        $new_dir_path = $chemin . "/" . $directory;
        if (!$this->fsObject->exists($new_dir_path)) {
            $old = umask(0);
            $this->fsObject->mkdir($new_dir_path, 0775);
            $this->fsObject->chown($new_dir_path, "www-data");
            $this->fsObject->chgrp($new_dir_path, "www-data");
            umask($old);
        }

    }

    public function removeFile(string $chemin, string $file)
    {
        try {
            $fullpath = $chemin . "/" . $file;
            if ($this->fsObject->exists($fullpath)) {
                $this->fsObject->remove($fullpath);
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating directory at" . $exception->getPath();
        }
    }

    public function moveFile(string $cheminSource, string $fileSource, string $cheminDestination, string $fileDestination)
    {
        try {
            $fullpathSource = $this->fullPathSource($cheminSource, $fileSource);
            $fullpathDestination = $cheminDestination . "/" . $fileDestination;

            if ($this->fsObject->exists($fullpathSource)) {
                $this->removeFile($cheminDestination, $fileDestination);
                $this->fsObject->copy($fullpathSource, $fullpathDestination);
            } else {
                dump('file not exist ' . $fullpathSource);
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating directory at" . $exception->getPath();
        }
    }

    public function fileExist(string $cheminSource, string $fileSource)
    {
        return $this->fsObject->exists($this->fullPathSource($cheminSource, $fileSource));
    }

    public function fullPathSource(string $cheminSource, string $fileSource)
    {
        return $cheminSource . "/" . $fileSource;
    }

    public function fileSize(string $cheminSource, string $fileSource)
    {
        if ($this->fileExist($cheminSource, $fileSource)) {
            return filesize($this->fullPathSource($cheminSource, $fileSource));
        }
        return 0;
    }
}