<?php

namespace SHUFLER\ShuflerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ChannelFlux
 *
 * @ORM\Table(name="channel_flux")
 * @ORM\Entity(repositoryClass="SHUFLER\ShuflerBundle\Entity\ChannelFluxRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="name", message="Un Channel de Flux existe déja avec ce nom, faut quand même pas pousser!")
 *
 */
class ChannelFlux implements ChannelInterface
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Assert\File(maxSize="200k", mimeTypes= {"image/jpeg", "image/png", "image/gif", "image/jpg"}, mimeTypesMessage = "Le fichier choisi ne correspond pas à un fichier valide")
     */
    private $image;
    
    /**
     * 
     * @var string
     */
    private $old_image;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="ProviderName", type="string", length=255,  nullable=true)
     */
    private $provider_name;
    
    /**
     * 
     * @var string
     * @ORM\Column(name="ProviderId", type="string", length=255, unique=true, nullable=true)
     */
    private $provider_id;
    
    /**
     *
     * @var \DateTime @ORM\Column(name="dateInsert", type="datetime")
     */
    private $dateInsert;
    
    public function __construct()
    {
        $this->dateInsert = new \Datetime();
    }
    
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ChannelFlux
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
     * Set Image
     *
     * @param string $image
     *
     * @return Flux
     */
    public function setImage($image = null)
    {
        $this->image = $image;
        
        return $this;
    }
    
    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     *
     * @param string $image
     * @return Flux
     */
    public function setOldImage($image = null) {
        $this->old_image = $image;
        return $this;
    }
    
    /**
     *
     * @return string
     */
    public function getOldImage() {
        return $this->old_image;
    }
    
    /**
     * 
     * @param string $providerName
     * @return \SHUFLER\ShuflerBundle\Entity\ChannelFlux
     */
    public function setProviderName($providerName = null){
        $this->provider_name = $providerName;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getProviderName() {
        return $this->provider_name;
    }

    /**
     * 
     * @param string $providerId
     * @return \SHUFLER\ShuflerBundle\Entity\ChannelFlux
     */
    public function setProviderId($providerId) {
        $this->provider_id = $providerId;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getProviderId() {
        return $this->provider_id;
    }
    
    /**
     * Delete Image
     *
     * @param string $filePath
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     */
    public function deleteLogo($filePath=null)
    {
        if(file_exists($filePath)) {
            unlink($filePath);
            return true;
        }
        return;
    }


    /**
     * Set dateInsert
     *
     * @param \DateTime $dateInsert
     *
     * @return ChannelFlux
     */
    public function setDateInsert($dateInsert)
    {
        $this->dateInsert = $dateInsert;

        return $this;
    }

    /**
     * Get dateInsert
     *
     * @return \DateTime
     */
    public function getDateInsert()
    {
        return $this->dateInsert;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isVideo() {
        return $this->provider_name ? true : false;
    }

 /**
  * {@inheritDoc}
  * @see \SHUFLER\ShuflerBundle\Entity\ChannelInterface::getTypeChannel()
  */
 public function getChannelClass() {
    return 'channel';
 }

}
