<?php

namespace SHUFLER\ShuflerBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;
    
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }
    
    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        if(!is_dir($this->getTargetDirectory())) mkdir($this->getTargetDirectory());
        $file->move($this->getTargetDirectory(), $fileName);
        
        return $fileName;
    }
      
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
    
}