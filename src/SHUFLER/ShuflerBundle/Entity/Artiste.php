<?php

namespace SHUFLER\ShuflerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Artiste
 *
 * @ORM\Table(name="artiste")
 * @ORM\Entity(repositoryClass="SHUFLER\ShuflerBundle\Entity\ArtisteRepository")
 */
class Artiste
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * @var string
     *
     * @ORM\Column(name="imageUrl", type="string", length=255, nullable=true)
     */
    private $imageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text", nullable=true)
     */
    private $bio;
    
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
     * @return Artiste
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
     * Set imageUrl
     *
     * @param string $url
     *
     * @return Artiste
     */
    public function setImageUrl($url)
    {
        $this->imageUrl = $url;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string
     */
    public function getimageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set bio
     *
     * @param string $bio
     *
     * @return Artiste
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    
}

