<?php
namespace SHUFLER\ShuflerBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use SHUFLER\ShuflerBundle\Entity\Flux;
use SHUFLER\ShuflerBundle\Service\FileUploader;

class LogoUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for lux entities
        if (!$entity instanceof Flux) {
            return;
        }

        $file = $entity->getImage();
        // only upload new files
        $entity->setImage($entity->getOldImage());
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setImage($fileName);
            if($entity->getOldImage()) {
                $this->deleteOldImage($entity->getOldImage());
            }
        }
    }
    
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        if (!$entity instanceof Flux) {
            return;
        }
        if ($entity->getImage() != null) {
            $fileName = $entity->getImage();
            $entity->setImage(new File($this->uploader->getTargetDirectory().'/'.$fileName));
        }
    }
    
    private function deleteOldImage($fileName) {
        if(file_exists($this->uploader->getTargetDirectory() . '/' . $fileName)) {
            unlink($this->uploader->getTargetDirectory() . '/' . $fileName);
        }
    }
}