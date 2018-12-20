<?php
namespace SHUFLER\ShuflerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Flux
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SHUFLER\ShuflerBundle\Entity\FluxRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"name", "type"}, message="Un Flux existe déja avec ce nom et ce type. Point trop n'en faut!")
 * @UniqueEntity(fields="url", message="Ce Flux est déjà enregistré. Laisse tomber!")
 */
class Flux
{

    const FLUX_TYPE = array(
        1 => 'rss',
        2 => 'podcast',
        3 => 'radio',
        4 => 'lien',
        5 => 'playlist'
    );

    const RADIO_TYPE = array(
        1 => 'généraliste',
        2 => 'rock',
        3 => 'jazz',
        4 => 'groove',
        5 => 'reggae',
        6 => 'disco',
        7 => 'funk',
        8 => 'classique',
        9 => 'electro',
        10 => 'bossa nova',
        11=>'informations',
        99 => 'autre'
    );

    const LINK_TYPE = array(
        101 => 'animation',
        102 => 'artistes',
        103 => 'BD',
        104 => 'Blogs',
        105 => 'Emissions',
        106 => 'Humour',
        107 => 'Jeux',
        108 => 'Liens divers',
        109 => 'Outils',
        110 => 'Presse',
        111 => 'Radios',
        112 => 'Tumbler',
        113 => 'Web dev',
        114 => 'Webdocs'
    );

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer")
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var string @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     *
     * @var string @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Assert\File(maxSize="200k", mimeTypes= {"image/jpeg", "image/png", "image/gif", "image/jpg"}, mimeTypesMessage = "Le fichier choisi ne correspond pas à un fichier valide")
     */
    private $image;
    
    /**
     * 
     * @var old_image
     */
    private $old_image;

    /**
     *
     * @var Integer @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     *
     * @var Integer @ORM\Column(name="mood", type="smallint", nullable=true)
     */
    private $mood;

    /**
     * @ORM\ManyToOne(targetEntity="ChannelFlux")
     * @ORM\JoinColumn(name="channel_flux_id", referencedColumnName="id")
     */
    private $channel;

    /**
     *
     * @var \DateTime @ORM\Column(name="dateInsert", type="datetime")
     */
    private $dateInsert;

    /**
     *
     * @var \SimpleXMLElement
     *
     */
    private $contenu;

    public function __construct()
    {
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
     * Set Mood
     *
     * @param integer $mood            
     *
     * @return \SHUFLER\ShuflerBundle\Entity\Flux
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

    /**
     * Set Category
     *
     * @param integer $mood            
     *
     * @return \SHUFLER\ShuflerBundle\Entity\Flux
     */
    public function setCategoryLink($mood)
    {
        return $this->setMood($mood);
    }

    /**
     * Get Category
     *
     * @return integer
     */
    public function getCategoryLink()
    {
        return $this->getMood();
    }

    /**
     *
     * @return \SimpleXMLElement
     *
     */
    public function loadContent()
    {
        if ($this->name !== 'Liberation') {
            
            if (@simplexml_load_file($this->url)->{'channel'}->{'item'}) {
                $this->contenu = @simplexml_load_file($this->url)->{'channel'}->{'item'};
            }
        } else {
            $this->contenu = @simplexml_load_file($this->url)->{'entry'};
        }
        
        return $this->contenu;
    }

    /**
     * Get Contenu
     *
     * @return \SimpleXMLElement
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Get channel
     *
     * @return \SHUFLER\ShuflerBundle\Entity\ChannelFlux
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     *
     * @param ChannelFlux $channel            
     *
     * @return \SHUFLER\ShuflerBundle\Entity\Flux
     */
    public function setChannel(ChannelFlux $channel = null)
    {
        $this->channel = $channel;
        return $this;
    }
    
    /**
     * Delete Image
     *
     * @param string $filePath
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteLogo($filePath=null)
    {
        if(file_exists($filePath)) {
            unlink($filePath);
            return true;
        }
        return;
    }
}
