<?php
namespace SHUFLER\ShuflerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="SHUFLER\ShuflerBundle\Entity\MoodRepository")
 *
 * @ExclusionPolicy("all")
 */
class Mood
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Expose
     * @Groups({"List","Details"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="SHUFLER\ShuflerBundle\Entity\Video", mappedBy="moods")
     * @ORM\JoinTable(name="video_mood")
     */
    private $videos;

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
     * @return Mood
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
     * Constructor
     */
    public function __construct()
    {
        $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add video
     *
     * @param \SHUFLER\ShuflerBundle\Entity\Video $video            
     *
     * @return Mood
     */
    public function addVideo(\SHUFLER\ShuflerBundle\Entity\Video $video)
    {
        $this->videos[] = $video;
        
        return $this;
    }

    /**
     * Remove video
     *
     * @param \SHUFLER\ShuflerBundle\Entity\Video $video            
     */
    public function removeVideo(\SHUFLER\ShuflerBundle\Entity\Video $video)
    {
        $this->videos->removeElement($video);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }
}
