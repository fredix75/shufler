<?php
namespace SHUFLER\ShuflerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
// use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SHUFLER\ShuflerBundle\Entity\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{

    /**
     *
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var string 
     * @ORM\Column(name="ext", type="string", length=4)
     */
    private $ext;

    /**
     * @Assert\Image(maxSize="200k")
     */
    private $file;

    private $tempFilename;

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
     * Set ext
     *
     * @param string $ext            
     *
     * @return Image
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
        
        return $this;
    }

    /**
     * Get ext
     *
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        
        // On vérifie si on avait déjà un fichier pour cette entité
        
        if (null !== $this->ext) {
            
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilename = $this->ext;
            
            // On réinitialise les valeurs des attributs ext
            $this->ext = null;
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    
    {
        
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            
            return;
        }
        
        // Le nom du fichier est son id, on doit juste stocker également son extension
        
        $this->ext = $this->file->guessExtension();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            return;
        }
        
        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilename) {
            
            $oldFile = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        
        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move($this->getUploadRootDir(), $this->id . '.' . $this->ext);
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    
    {
        
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->ext;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    
    {
        
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFilename)) {
            
            // On supprime le fichier
            
            unlink($this->tempFilename);
        }
    }

    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
        return 'uploads/img';
    }

    protected function getUploadRootDir()
    
    {
        
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }
}

