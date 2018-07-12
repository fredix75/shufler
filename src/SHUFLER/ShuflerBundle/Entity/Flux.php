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
 * @ORM\HasLifecycleCallbacks()
 */
class Flux
{

    const FLUX_TYPE = array(
        1 => 'rss',
        2 => 'podcast',
        3 => 'radio',
        4 => 'lien'
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
        99 => 'autre'
    );

    const LINK_TYPE = array(
        101 => 'animation',
        102 => 'artistes',
        103 => 'BD',
        104 => 'Blogs',
        105 => 'Emmissions',
        106 => 'Humour',
        107 => 'Jeux',
        108 => 'Liens divers',
        109 => 'Outils',
        110 => 'Presse',
        111 => 'Radios',
        112 => 'Tumbler',
        113 => 'Web dev',
        114 => 'Webdocs',
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
     * @ORM\ManyToOne(targetEntity="SHUFLER\ShuflerBundle\Entity\Image", cascade={"persist","remove"})
     * @Assert\Valid()
     */
    private $logo;

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
     * @ORM\ManyToOne(targetEntity="ChannelFlux", cascade={"persist"})
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

    /**
     * Get Logo of Flux
     *
     * @return string
     */
    public function getPic()
    {
        return Image::UPLOAD_DIR . '/' . $this->getLogo()->getId() . '.' . $this->getLogo()->getExt();
    }

   /**
   * Get Logo of Channel
   * 
   * @return NULL|string
   */
    public function getChannelLogo()
    {
        if (!$this->getChannel()->getImage()) return null;
        return Image::UPLOAD_CHANNEL_DIR. '/' . $this->getChannel()->getImage()->getId() . '.' . $this->getChannel()->getImage()->getExt();
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
     *
     * @return \SimpleXMLElement
     *
     */
    public function loadContent()
    {
        if ($this->name != 'Liberation') {
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
    public function setChannel(ChannelFlux $channel)
    {
        $this->channel = $channel;
        return $this;
    }
}
