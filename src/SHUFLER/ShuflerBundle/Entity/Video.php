<?php
namespace SHUFLER\ShuflerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Video
 *
 * @ORM\Entity(repositoryClass="SHUFLER\ShuflerBundle\Entity\VideoRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @ExclusionPolicy("all")
 */
class Video
{

    const MAX_LIST = 12;
    
    const CATEGORY_LIST = [
        1 => 'Anim\'',
        2 => 'Music Moment',
        3 => 'Strange',
        4 => 'Funny',
        6 => 'Seen on Tv',
        8 => 'Movie Time',
        9 => 'Nature'
    ];
    
    const PERIOD_LIST = [
        '2016-2030' => '2016-2030',
        '2001-2015' => '2001-2015',
        '1986-2000' => '1986-2000',
        '1971-1985' => '1971-1985',
        '1956-1970' => '1956-1970',
        '1940-1955' => '1940-1955',
        '<1939' => '<1939'
    ];
    
    const GENRE_LIST = [
        -1 => 'autre',
        1 => 'Jazz/Blues',
        2 => 'Rock n\'Roll',
        3 => 'Rock/Pop',
        4 => 'Soul/Funk',
        5 => 'Reggae/Ragga/Dub',
        6 => 'Chanson',
        7 => 'Punk/Alternatif',
        8 => 'Hard-Rock',
        9 => 'Blues Rock',
        10 => 'Electro/DJs',
        11 => 'World',
        12 => 'Roots/Folk Rock',
        13 => 'Rock progressif/psyché',
        14 => 'Variétés / Pop',
        15 => 'Trip-Hop',
        16 => 'Afrobeat'	
    ];
    
    const PRIORITY_LIST = [1,2,3,4];
    
    const YOUTUBE_EMBED = 'www.youtube.com';
        
    const YOUTUBE_API = 'http://img.youtube.com/vi';
    
    const YOUTUBE_WATCH = 'https://www.youtube.com/watch?v=';
    
    const DAILYMOTION_EMBED =  'www.dailymotion.com';
    
    const DAILYMOTION_VIDEO = 'http://www.dailymotion.com/video/';
       
    const DAILYMOTION_API = 'http://www.dailymotion.com/services/oembed?url=';
    
    const VIMEO_PLAYER = 'player.vimeo.com';
    
   const VIMEO_API = 'http://vimeo.com/api/v2/video';
    
    const VIDEO_UNAVAILABLE = 'http://s3.amazonaws.com/colorcombos-images/users/1/color-schemes/color-scheme-2-main.png?v=20111009081033';
    
    
        
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *     
     * @Expose
     * @Groups({"List","Details"})
     *     
     */
    private $id;

    /**
     *
     * @var string
     * 
     * @ORM\Column(name="titre", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Ce titre paraît suspect")
     *     
     * @Expose
     * @Groups({"List","Details"})
     *     
     */
    private $titre;

    /**
     *
     * @var string
     * 
     * @ORM\Column(name="auteur", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Cet auteur paraît suspect")
     *     
     * @Expose
     * @Groups({"List","Details"})
     *     
     */
    private $auteur;

    /**
     *
     * @var string
     * 
     * @ORM\Column(name="lien", type="string", length=255)
     *     
     * @Expose
     * @Groups({"List","Details"})
     *     
     */
    private $lien;

    /**
     *
     * @var string
     * 
     * @ORM\Column(name="chapo", type="string", length=255, nullable=true)
     *     
     * @Expose
     *     
     */
    private $chapo;

    /**
     *
     * @var string
     * @ORM\Column(name="texte", type="text", nullable=true)
     */
    private $texte;

    /**
     *
     * @var integer
     * @ORM\Column(name="annee", type="integer",nullable=true)
     * @Assert\Range(min=1895, max=2030)
     *     
     * @Expose
     *     
     */
    private $annee;

    /**
     *
     * @var integer 
     * @ORM\Column(name="categorie", type="smallint")
     *     
     * @Expose
     *     
     */
    private $categorie;

    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="genre", type="smallint", nullable=true)
     *     
     * @Expose
     *     
     */
    private $genre;

    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="priorite", type="smallint")
     *     
     * @Expose
     *     
     */
    private $priorite;

    /**
     *
     * @var string
     * 
     * @ORM\Column(name="periode", type="string", length=9)
     *     
     * @Expose
     *     
     */
    private $periode;

    /**
     * @ORM\ManyToMany(targetEntity="SHUFLER\ShuflerBundle\Entity\Mood", cascade={"persist"}, inversedBy="videos")
     * @ORM\JoinTable(name="video_mood",
     *  joinColumns={ @ORM\JoinColumn(name="video_id", referencedColumnName="id")},
     *  inverseJoinColumns={ @ORM\JoinColumn(name="mood_id", referencedColumnName="id")}
     * )
     *
     * @Expose
     */
    private $moods;

    /**
     *
     * @var boolean
     * 
     * @ORM\Column(name="published", type="boolean", nullable=true)
     *     
     * @Expose
     *     
     */
    private $published = true;

    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="date_insert", type="datetime")
     */
    private $dateInsert;

    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    private $dateUpdate;

    public function __construct()
    {
        $this->dateInsert = new \Datetime();
        $this->moods = new ArrayCollection();
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
     * Set titre
     *
     * @param string $titre            
     *
     * @return Video
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
        
        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set auteur
     *
     * @param string $auteur            
     *
     * @return Video
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
        
        return $this;
    }

    /**
     * Get auteur
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set lien
     *
     * @param string $lien            
     *
     * @return Video
     */
    public function setLien($lien)
    {
        $this->lien = $lien;
        
        $pattern1 = 'https://vimeo.com/channels/staffpicks/';
        if (strripos($this->lien, $pattern1) !== false) {
            $this->lien = str_replace($pattern1, '//player.vimeo.com/video/', $this->lien);
        }
        
        $pattern2 = 'https://vimeo.com/';
        if (strripos($this->lien, $pattern2) !== false) {
            $this->lien = str_replace($pattern2, '//player.vimeo.com/video/', $this->lien);
        }
        
        $pattern4 = 'https://player.vimeo.com/';
        if (strripos($this->lien, $pattern4) !== false) {
            $this->lien = str_replace($pattern4, '//player.vimeo.com/video/', $this->lien);
        }
        
        $pattern3 = 'http://www.dailymotion.com/video/';
        if (strripos($this->lien, $pattern3) !== false) {
            $this->lien = str_replace($pattern3, '//www.dailymotion.com/embed/video/', $this->lien);
        }
        
        return $this;
    }

    /**
     * Get lien
     *
     * @return string
     */
    public function getLien()
    {
        $lien = $this->lien;
        $pattern = "https://www.youtube.com/watch?v=";
        $vid = "//www.youtube.com/embed/";
        if (mb_substr($lien, 0, strlen($pattern)) == $pattern) {
            $vid .= substr($lien, strlen($pattern));
            $lien = $vid;
        }
        
        return $lien;
    }

    /**
     * Set chapo
     *
     * @param string $chapo            
     *
     * @return Video
     */
    public function setChapo($chapo)
    {
        $this->chapo = $chapo;
        
        return $this;
    }

    /**
     * Get chapo
     *
     * @return string
     */
    public function getChapo()
    {
        return $this->chapo;
    }

    /**
     * Set texte
     *
     * @param string $texte            
     *
     * @return Video
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;
        
        return $this;
    }

    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set annee
     *
     * @param integer $annee            
     *
     * @return Video
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;
        
        return $this;
    }

    /**
     * Get annee
     *
     * @return integer
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set categorie
     *
     * @param integer $categorie            
     *
     * @return Video
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        
        return $this;
    }

    /**
     * Get categorie
     *
     * @return integer
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set genre
     *
     * @param integer $genre            
     *
     * @return Video
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
        
        return $this;
    }

    /**
     * Get genre
     *
     * @return integer
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set priorite
     *
     * @param integer $priorite            
     *
     * @return Video
     */
    public function setPriorite($priorite)
    {
        $this->priorite = $priorite;
        
        return $this;
    }

    /**
     * Get priorite
     *
     * @return integer
     */
    public function getPriorite()
    {
        return $this->priorite;
    }

    /**
     * Set periode
     *
     * @param string $periode            
     *
     * @return Video
     */
    public function setPeriode($periode)
    {
        $this->periode = $periode;
        
        return $this;
    }

    /**
     * Get periode
     *
     * @return string
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * Set dateInsert
     *
     * @param \DateTime $dateInsert            
     *
     * @return Video
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
     * Set published
     *
     * @param boolean $published            
     *
     * @return Video
     */
    public function setPublished($published)
    {
        $this->published = $published;
        
        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Get moods
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMoods()
    {
        return $this->moods;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate            
     *
     * @return Video
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;
        
        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Update Date 
     * 
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setDateUpdate(new \Datetime());
    }

    /**
     * Add mood
     *
     * @param \SHUFLER\ShuflerBundle\Entity\Mood $mood            
     *
     * @return Video
     */
    public function addMood(\SHUFLER\ShuflerBundle\Entity\Mood $mood)
    {
        $this->moods[] = $mood;
        
        return $this;
    }

    /**
     * Remove mood
     *
     * @param \SHUFLER\ShuflerBundle\Entity\Mood $mood            
     */
    public function removeMood(\SHUFLER\ShuflerBundle\Entity\Mood $mood)
    {
        $this->moods->removeElement($mood);
        $mood->removeElement($this);
    }

    /**
     * Is Period coherent with date Validator
     * 
     * @param ExecutionContextInterface $context
     * @Assert\Callback
     */
    public function isGoodPeriod(ExecutionContextInterface $context)
    {
        if ($this->getAnnee() != '' && $this->getPeriode() != - 1) {
            $periode = $this->getPeriode();
            $finPeriode = (int) substr($periode, - 4);
            $debutPeriode = (substr($periode, 0, 1) != '<') ? (int) substr($periode, 0, 4) : 0;
            if ((int) $this->getAnnee() < $debutPeriode || (int) $this->getAnnee() > $finPeriode) {
                $context->addViolation('La Période ne correspond pas à l\'année');
            }
        }
    }
}
