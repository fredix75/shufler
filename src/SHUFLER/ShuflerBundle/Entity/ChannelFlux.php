<?php

namespace SHUFLER\ShuflerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ChannelFlux
 *
 * @ORM\Table(name="channel_flux")
 * @ORM\Entity(repositoryClass="SHUFLER\ShuflerBundle\Entity\ChannelFluxRepository")
 */
class ChannelFlux
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
     * @var Image
     * 
     * @ORM\OneToOne(targetEntity="SHUFLER\ShuflerBundle\Entity\Image", cascade={"persist"})
     */
    private $image;
           
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
     * Set image
     *
     * @param \SHUFLER\ShuflerBundle\Entity\Image $image
     *
     * @return ChannelFlux
     */
    public function setImage(\SHUFLER\ShuflerBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \SHUFLER\ShuflerBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }


}
