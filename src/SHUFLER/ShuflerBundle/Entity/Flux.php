<?php

namespace SHUFLER\ShuflerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Flux
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SHUFLER\ShuflerBundle\Entity\FluxRepository")
 */
class Flux
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    
    /**
     * @ORM\ManyToOne(targetEntity="SHUFLER\ShuflerBundle\Entity\Image", cascade={"persist","remove"})
     * @Assert\Valid()
     */
    private $logo;

    /**
     * @var Integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;
    
    /**
     * @var Integer
     *
     * @ORM\Column(name="mood", type="smallint")
     */
    private $mood;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateInsert", type="datetime")
     */
    private $dateInsert;  
    
    public function __construct(){
    	$this->dateInsert = new \Datetime();
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
     * Set name
     *
     * @param string $name
     *
     * @return Flux
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
     * Set url
     *
     * @param string $url
     *
     * @return Flux
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Flux
     */
    public function setLogo(Image $logo = null)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
 
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set dateInsert
     *
     * @param \DateTime $dateInsert
     *
     * @return Flux
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
    
    public function getPic(){
    	return $this->getLogo()->getUploadDir().'/'.$this->getLogo()->getId().'.'.$this->getLogo()->getExt();
    }  
    

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Flux
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Set mood
     *
     * @param integer $mood
     *
     * @return Flux
     */
    public function setMood($mood)
    {
        $this->mood = $mood;

        return $this;
    }

    /**
     * Get mood
     *
     * @return integer
     */
    public function getMood()
    {
        return $this->mood;
    }
}
