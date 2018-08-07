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
 *
 * @ExclusionPolicy("all")
 */
class Video
{

    const MAX_LIST = 11;

    const MAX_LIST_COUCH = 99;

    const INDEX_MAX_ANIM = 4;
    
    const INDEX_MAX_MUSIC = 15;
    
    const INDEX_MAX_AUTRES = 3;
    
    const INDEX_MAX_TOTAL = self::INDEX_MAX_ANIM + self::INDEX_MAX_MUSIC + self::INDEX_MAX_AUTRES;
       
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
        16 => 'Afrobeat',
        - 1 => 'autre',
    ];

    const PRIORITY_LIST = [
        1,
        2,
        3,
        4
    ];

    /**
     * *********************************
     */
    const YOUTUBE = 'youtube.com';

    const YOUTUBE_WWW = 'www.' . self::YOUTUBE;

    const YOUTUBE_API = 'http://img.' . self::YOUTUBE . '/vi/';
    
    const YOUTUBE_EMBED = 'https://' . self::YOUTUBE_WWW . '/embed/';

    const YOUTUBE_WATCH = 'https://' . self::YOUTUBE_WWW . '/watch?v=';

    /**
     * *********************************
     */
    const DAILYMOTION = 'dailymotion.com';

    const DAILYMOTION_WWW = 'www.' . self::DAILYMOTION;

    const DAILYMOTION_VIDEO = 'http://' . self::DAILYMOTION_WWW . '/video/';
    
    const DAIYMOTION_EMBED = '//' . self::DAILYMOTION_WWW . '/embed/video/';

    const DAILYMOTION_API = 'http://' . self::DAILYMOTION_WWW . '/services/oembed?url=';

    /**
     * *********************************
     */
    const VIMEO = 'vimeo.com';

    const VIMEO_HTTPS = 'https://' . self::VIMEO . '/';
    
    const VIMEO_PLAYER = 'player.' . self::VIMEO;

    const VIMEO_PLAYER_HTTPS = 'https://' . self::VIMEO_PLAYER . '/';
    
    const VIMEO_VIDEO = '//player.' . self::VIMEO . '/video/';
    
    const VIMEO_API = 'http://' . self::VIMEO . '/api/v2/video/';

    const VIMEO_STAFFPICKS = 'https://' . self::VIMEO . '/channels/staffpicks/';
    
    
    /**
     * *********************************
     */
    const VIDEO_UNAVAILABLE = 'http://s3.amazonaws.com/colorcombos-images/users/1/color-schemes/color-scheme-2-main.png?v=20111009081033';

    /**
     * Id
     *
     * @var integer @ORM\Column(name="id", type="integer")
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="AUTO")
     *     
     *      @Expose
     *      @Groups({"List","Details"})
     *     
     */
    private $id;

    /**
     * Titre
     *
     * @var string @ORM\Column(name="titre", type="string", length=255)
     *      @Assert\Length(min=2, minMessage="Ce titre paraît suspect")
     *     
     *      @Expose
     *      @Groups({"List","Details"})
     *     
     */
    private $titre;

    /**
     * Auteur
     *
     * @var string @ORM\Column(name="auteur", type="string", length=255)
     *      @Assert\Length(min=2, minMessage="Cet auteur paraît suspect")
     *     
     *      @Expose
     *      @Groups({"List","Details"})
     *     
     */
    private $auteur;

    /**
     * Lien
     *
     * @var string @ORM\Column(name="lien", type="string", length=255)
     *     
     *      @Expose
     *      @Groups({"List","Details"})
     *     
     */
    private $lien;

    /**
     * Chapo
     *
     * @var string @ORM\Column(name="chapo", type="string", length=255, nullable=true)
     *     
     *      @Expose
     *     
     */
    private $chapo;

    /**
     * Texte
     *
     * @var string @ORM\Column(name="texte", type="text", nullable=true)
     */
    private $texte;

    /**
     * Année
     *
     * @var integer @ORM\Column(name="annee", type="integer",nullable=true)
     *      @Assert\Range(min=1895, max=2030)
     *     
     *      @Expose
     *     
     */
    private $annee;

    /**
     * Catégorie
     *
     * @var integer @ORM\Column(name="categorie", type="smallint")
     *     
     *      @Expose
     *     
     */
    private $categorie;

    /**
     * Genre
     *
     * @var integer @ORM\Column(name="genre", type="smallint", nullable=true)
     *     
     *      @Expose
     *     
     */
    private $genre;

    /**
     * Priorité
     *
     * @var integer @ORM\Column(name="priorite", type="smallint")
     *     
     *      @Expose
     *     
     */
    private $priorite;

    /**
     * Période
     *
     * @var string @ORM\Column(name="periode", type="string", length=9)
     *     
     *      @Expose
     *     
     */
    private $periode;

    /**
     * Moods
     *
     * @ORM\ManyToMany(targetEntity="SHUFLER\ShuflerBundle\Entity\Mood", cascade={"persist"}, inversedBy="videos")
     * @ORM\JoinTable(name="video_mood",
     * joinColumns={ @ORM\JoinColumn(name="video_id", referencedColumnName="id")},
     * inverseJoinColumns={ @ORM\JoinColumn(name="mood_id", referencedColumnName="id")}
     * )
     *
     * @Expose
     */
    private $moods;

    /**
     * Published
     *
     * @var boolean @ORM\Column(name="published", type="boolean", nullable=true)
     *     
     *      @Expose
     *     
     */
    private $published = true;

    /**
     * Date d'insertion
     *
     * @var \DateTime @ORM\Column(name="date_insert", type="datetime")
     */
    private $dateInsert;

    /**
     * Date d'Update
     *
     * @var \DateTime @ORM\Column(name="date_update", type="datetime", nullable=true)
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

        if (strripos($this->lien, self::YOUTUBE_WATCH) !== false) {
            $this->lien = str_replace(self::YOUTUBE_WATCH, self::YOUTUBE_EMBED, $this->lien);
        }
        
        if (strripos($this->lien, self::VIMEO_STAFFPICKS) !== false) {
            $this->lien = str_replace(self::VIMEO_STAFFPICKS, self::VIMEO_VIDEO, $this->lien);
        }
        
        if (strripos($this->lien, self::VIMEO_HTTPS) !== false) {
            $this->lien = str_replace(self::VIMEO_HTTPS, self::VIMEO_VIDEO, $this->lien);
        }
        
        if (strripos($this->lien, self::VIMEO_PLAYER_HTTPS) !== false) {
            $this->lien = str_replace(self::VIMEO_PLAYER_HTTPS, self::VIMEO_VIDEO, $this->lien);
        }
        
        if (strripos($this->lien, self::DAILYMOTION_VIDEO) !== false) {
            $this->lien = str_replace(self::DAILYMOTION_VIDEO, self::DAILYMOTION_EMBED, $this->lien);
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
        if (strripos($this->lien, self::YOUTUBE_WATCH) !== false) {
            $this->lien = str_replace(self::YOUTUBE_WATCH, self::YOUTUBE_EMBED, $this->lien);
        }
        return $this->lien;
               
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
     *            @Assert\Callback
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
