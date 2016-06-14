<?php
namespace SHUFLER\ShuflerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Video
 *
 * @ORM\Entity(repositoryClass="SHUFLER\ShuflerBundle\Entity\VideoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Video
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
     * @ORM\Column(name="titre", type="string", length=255)
     * @Assert\Length(min=2,minMessage="Ce titre paraît suspect")
     */
    private $titre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255)
     * @Assert\Length(min=2,minMessage="Cet auteur paraît suspect")
     */
    private $auteur;

    /**
     * @var string
     *
     * @ORM\Column(name="lien", type="string", length=255)
     */
    private $lien;

    /**
     * @var string
     *
     * @ORM\Column(name="chapo", type="string", length=255, nullable=true)
     */
    private $chapo;

    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="text", nullable=true)
     */
    private $texte;

    /**
     * @var integer
     *
     * @ORM\Column(name="annee", type="integer",nullable=true)
     * @Assert\Range(min=1895, max=2030)
     */
    private $annee;

    /**
     * @var integer
     *
     * @ORM\Column(name="categorie", type="smallint")
     */
    private $categorie;

    /**
     * @var integer
     *
     * @ORM\Column(name="genre", type="smallint", nullable=true)
     */
    private $genre;

    /**
     * @var integer
     *
     * @ORM\Column(name="priorite", type="smallint")
     */
    private $priorite;

    /**
     * @var string
     *
     * @ORM\Column(name="periode", type="string", length=9)
     */
    private $periode;

	/**
     * @ORM\ManyToMany(targetEntity="SHUFLER\ShuflerBundle\Entity\Mood", cascade={"persist"}, inversedBy="videos")
	 * @ORM\JoinTable(name="video_mood",
	 * 		joinColumns={ @ORM\JoinColumn(name="video_id", referencedColumnName="id")},
	 *		inverseJoinColumns={ @ORM\JoinColumn(name="mood_id", referencedColumnName="id")}
	 * )
     */
  	private $moods;
 

    
    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean", nullable=true)
     */
    private $published=true;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_insert", type="datetime")
     */
    private $dateInsert;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    private $dateUpdate;
    
    /**
     * @var string
     *
     */
    private $lien2;
    
    /**
     * @var string
     *
     */
	private $imgVideo;
    
    public function __construct(){
    	$this->dateInsert = new \Datetime();
    	$this->moods   = new ArrayCollection();
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
        
        $pattern1='https://vimeo.com/channels/staffpicks/';
        if(strripos($this->lien,$pattern1)!==false){
        	$this->lien=str_replace($pattern1,'//player.vimeo.com/video/',$this->lien);
        }
        
        $pattern2='https://vimeo.com/';        
        if(strripos($this->lien,$pattern2)!==false){
        	$this->lien=str_replace($pattern2,'//player.vimeo.com/video/',$this->lien);
        }
        
        $pattern4='https://player.vimeo.com/';
        if(strripos($this->lien,$pattern4)!==false){
        	$this->lien=str_replace($pattern4,'//player.vimeo.com/video/',$this->lien);
        }
        
        $pattern3='http://www.dailymotion.com/video/';
        if(strripos($this->lien,$pattern3)!==false){
        	$this->lien=str_replace($pattern3,'//www.dailymotion.com/embed/video/',$this->lien);
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
    	$lien=$this->lien;
     	$pattern="https://www.youtube.com/watch?v=";
    	$vid="//www.youtube.com/embed/";
    	if(mb_substr($lien, 0, strlen($pattern))==$pattern){
    		$vid.=substr($lien,strlen($pattern));
    		$lien=$vid;
    		
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
     * Get imgVideo
     *
     * @return string
     */
    public function getImgVideo()
    {
    	return $this->getImgVideoByLink('100%');
    }

    /**
     * Get Lien2
     *
     * @return string
     */
    public function getLien2(){
    	return $this->lien2=$this->getLienPOP();
    }

    
    /**
     * Get ImgVideoByLink
     *
     * @return string
     */
    protected function getImgVideoByLink($width){
    	$lien=$this->lien;
    	$yt1="http://www.youtube.com/embed/";
    	$yt2="//www.youtube.com/embed/";
    	$yt3="https://www.youtube.com/watch?v=";
    	$vid="http://img.youtube.com/vi/";
    	$dm="http://www.dailymotion.com/embed/video/";
    	$dm2="//www.dailymotion.com/embed/video/";
    	$vid2="http://www.dailymotion.com/thumbnail/video/";
    	$vim="http://player.vimeo.com/video/";
    	$vim2="//player.vimeo.com/video/";
    	  	
    	if(mb_substr($lien, 0, strlen($yt1))==$yt1){
    		$vid.=substr($lien,strlen($yt1));
    		$vid.="/0.jpg";
    		$frame="<img class='embed-responsive-item' src='".$vid."' width=".$width." />";   		
    	}elseif(mb_substr($lien, 0, strlen($yt2))==$yt2){
    		$vid.=substr($lien,strlen($yt2));
    		$vid.="/0.jpg";
    		$frame="<img class='embed-responsive-item' src='".$vid."' width=".$width." />";
    	}elseif(mb_substr($lien, 0, strlen($yt3))==$yt3){
    		$vid.=substr($lien,strlen($yt3));
    		$vid.="/0.jpg";
    		$frame="<img class='embed-responsive-item' src='".$vid."' width=".$width." />";
    	}elseif(mb_substr($lien, 0, strlen($vim))==$vim){
    		$id=substr($lien,strlen($vim));
    		try{	
    			$data = file_get_contents("http://vimeo.com/api/v2/video/$id.json");
    		}catch(\Exception $e){
    			error_log($e->getMessage());
    			$data=null;
    		}
    		if($data!=null && $data = json_decode($data)){
	    		$frame="<img class='embed-responsive-item' src='".$data[0]->thumbnail_medium."' width=".$width." />";
    		}else{
	   			$frame="<img class='embed-responsive-item' src='http://s3.amazonaws.com/colorcombos-images/users/1/color-schemes/color-scheme-2-main.png?v=20111009081033' width=".$width." />";
    		}
    	}elseif(mb_substr($lien, 0, strlen($vim2))==$vim2){
    		$id=substr($lien,strlen($vim2));
    		try{    			    
    			if($id!=112297136){ 				//Exception sur id (pas le choix) --- #la merde
    				$data = file_get_contents("http://vimeo.com/api/v2/video/$id.json");
    			}else{
    				$data=null;
    			}
    		}catch(\Exception $e){
    			error_log($e->getMessage());
    			$data=null;
    		}
    	    if($data!=null && $data = json_decode($data)){
	    		$frame="<img class='embed-responsive-item' src='".$data[0]->thumbnail_medium."' width=".$width." />";
    		}else{
    			$frame="<img class='embed-responsive-item' src='http://s3.amazonaws.com/colorcombos-images/users/1/color-schemes/color-scheme-2-main.png?v=20111009081033' width=".$width." />";
    		}
    	}elseif(mb_substr($lien, 0, strlen($dm))==$dm){
    		try{
    			$data = file_get_contents('http://www.dailymotion.com/services/oembed?url='.$lien);
    		}catch(\Exception $e){
    			error_log($e->getMessage());
    			$data=null;
    		}
    		if($data && $data = json_decode($data)){
    			$frame="<img class='embed-responsive-item' src='".$data->thumbnail_url."' width=".$width." />";
    		}else{
    			$frame="<img class='embed-responsive-item' src='http://s3.amazonaws.com/colorcombos-images/users/1/color-schemes/color-scheme-2-main.png?v=20111009081033' width=".$width." />";
    		}
    		/*
    		$vid2.=substr($lien,strlen($dm));
    		$frame="<img class='embed-responsive-item' src='".$vid2."' width=".$width." />";
    		*/
    	}elseif(mb_substr($lien, 0, strlen($dm2))==$dm2){
    		try{	
    			$data = file_get_contents('http://www.dailymotion.com/services/oembed?url=http:'.$lien);
    		}catch(\Exception $e){
    			error_log($e->getMessage());
    			$data=null;
    		}
    		if($data && $data = json_decode($data)){
    			$frame="<img class='embed-responsive-item' src='".$data->thumbnail_url."' width=".$width." />";
    		}else{
    			$frame="<img class='embed-responsive-item' src='http://s3.amazonaws.com/colorcombos-images/users/1/color-schemes/color-scheme-2-main.png?v=20111009081033' width=".$width." />";
    		}
    		/*
    		$vid2.=substr($lien,strlen($dm2));
    		$frame="<img class='embed-responsive-item' src='".$vid2."' width=".$width." />";
    		*/
    	}else{
   			$frame="<img class='embed-responsive-item' src='http://s3.amazonaws.com/colorcombos-images/users/1/color-schemes/color-scheme-2-main.png?v=20111009081033' width=".$width." />";
    	}
    	return $frame;
    }
    
    /**
     * Get LienPOP
     *
     * @return string
     */
    protected function getLienPOP(){
   		$lien=$this->lien;
    	
    	$yt1="http://www.youtube.com/embed/";
   		$yt2="//www.youtube.com/embed/";
   		$vid="https://www.youtube.com/watch?v=";
   		$dm="http://www.dailymotion.com/embed/video/";
   		$dm2="//www.dailymotion.com/embed/video/";
   		$vid2="http://www.dailymotion.com/video/";
    	
   		if(mb_substr($lien, 0, strlen($yt1))==$yt1){
   			$link=$vid.substr($lien,strlen($yt1));
   		}elseif(mb_substr($lien, 0, strlen($yt2))==$yt2){
   			$link=$vid.substr($lien,strlen($yt2));
   		}elseif(mb_substr($lien, 0, strlen($dm))==$dm){
   			$link=$vid2.substr($lien,strlen($dm));
   		}elseif(mb_substr($lien, 0, strlen($dm2))==$dm2){
   			$link=$vid2.substr($lien,strlen($dm2));
   		}else{
   			$link=$lien;
   		}
    	
   		return $link;   	
    	
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
}
