<?php
namespace SHUFLER\ShuflerBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use SHUFLER\ShuflerBundle\Entity\Flux;
use SHUFLER\ShuflerBundle\Entity\ChannelFlux;
use SHUFLER\ShuflerBundle\Service\FileUploader;

class ImageUploadListener
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
        // upload only works for Flux entities
        if (! $entity instanceof Flux && ! $entity instanceof ChannelFlux) {
            return;
        }
        
        $file = $entity->getImage();

        $entity->setImage($entity->getOldImage());
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setImage($fileName);
            if ($entity->getOldImage()) {
                if(is_callable([$entity, 'deleteLogo'])){
                    $entity->deleteLogo($this->uploader->getTargetDirectory() . '/' . $entity->getOldImage());
                }
            }
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        if (! $entity instanceof Flux && ! $entity instanceof ChannelFlux) {
            return;
        }
        if(is_callable([$entity, 'deleteLogo'])){
            $entity->deleteLogo($this->uploader->getTargetDirectory() . '/' . $entity->getOldImage());
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        if (! $entity instanceof Flux && ! $entity instanceof ChannelFlux) {
            return;
        }

        if ($entity->getImage() != null) {
            if(file_exists($this->uploader->getTargetDirectory() . '/' . $entity->getImage())) {
                $fileName = $entity->getImage();
                if (! $entity->getOldImage()) {
                    $entity->setOldImage($entity->getImage());
                }
                $entity->setImage(new File($this->uploader->getTargetDirectory() . '/' . $fileName));
            } elseif(@get_headers($entity->getImage())) {
                $entity->setOldImage($entity->getImage());
                $entity->setImage();
            } else {
                $entity->setImage();
            }
        }
    }
}