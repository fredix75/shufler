<?php

namespace SHUFLER\ShuflerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MusicTrack
 *
 * @ORM\Table(name="music_track")
 * @ORM\Entity(repositoryClass="SHUFLER\ShuflerBundle\Entity\MusicTrackRepository")
 */
class MusicTrack
{
    
    const MAX_LIST = 500;
    
    const TRACKS_COLUMNS_TO_DISPLAY_DB = [
      'auteur', 'titre', 'numero', 'album', 'annee', 'artiste', 'genre','duree', 'pays', 'bitrate', 'note'
    ];
    
    const TRACKS_COLUMNS_TO_DISPLAY_DT = [
        'Auteur', 'Titre', 'Numero', 'Album', 'Année', 'Artiste', 'Genre','Durée', 'Pays', 'Bitrate', 'Note'
    ];
    
    const ALBUMS_COLUMNS_TO_DISPLAY_DB = [
        'album', 'artiste', 'annee', 'genre'
    ];
    
    const ALBUMS_COLUMNS_TO_DISPLAY_DT = [
        'Album', 'Artiste', 'Année', 'Genre'
    ];
    
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255)
     */
    private $auteur;

    /**
     * @var string
     *
     * @ORM\Column(name="album", type="string", length=255)
     */
    private $album;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="annee", type="string", length=4, nullable=true)
     */
    private $annee;

    /**
     * @var string
     *
     * @ORM\Column(name="artiste", type="string", length=255)
     */
    private $artiste;

    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", length=255)
     */
    private $genre;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255, nullable=true)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="duree", type="string", length=255)
     */
    private $duree;

    /**
     * @var string
     *
     * @ORM\Column(name="bitrate", type="string", length=255, nullable=true)
     */
    private $bitrate;

    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer", nullable=true)
     */
    private $note;


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
     * Set titre
     *
     * @param string $titre
     *
     * @return MusicTrack
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
     * @return MusicTrack
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
     * Set album
     *
     * @param string $album
     *
     * @return MusicTrack
     */
    public function setAlbum($album)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return string
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return MusicTrack
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set annee
     *
     * @param string $annee
     *
     * @return MusicTrack
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return string
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set artiste
     *
     * @param string $artiste
     *
     * @return MusicTrack
     */
    public function setArtiste($artiste)
    {
        $this->artiste = $artiste;

        return $this;
    }

    /**
     * Get artiste
     *
     * @return string
     */
    public function getArtiste()
    {
        return $this->artiste;
    }

    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return MusicTrack
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return MusicTrack
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set duree
     *
     * @param string $duree
     *
     * @return MusicTrack
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return string
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set bitrate
     *
     * @param string $bitrate
     *
     * @return MusicTrack
     */
    public function setBitrate($bitrate)
    {
        $this->bitrate = $bitrate;

        return $this;
    }

    /**
     * Get bitrate
     *
     * @return string
     */
    public function getBitrate()
    {
        return $this->bitrate;
    }

    /**
     * Set note
     *
     * @param integer $note
     *
     * @return MusicTrack
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return int
     */
    public function getNote()
    {
        return $this->note;
    }
}

