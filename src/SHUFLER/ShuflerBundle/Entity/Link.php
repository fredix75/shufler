<?php
namespace SHUFLER\ShuflerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Link
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SHUFLER\ShuflerBundle\Entity\LinkRepository")
 */
class Link
{

    /**
     *
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     *
     * @var string
     * @ORM\Column(name="link", type="string", length=255)
     */
    private $link;

    /**
     *
     * @var string
     * @ORM\Column(name="category", type="string", length=50)
     */
    private $category;

    /**
     *
     * @var string
     * @ORM\Column(name="pic", type="string", nullable=true, length=255)
     */
    private $pic;

    /**
     *
     * @var \DateTime
     * @ORM\Column(name="dateInsert", type="datetime")
     */
    private $dateInsert;

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
     * @return Link
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
     * Set link
     *
     * @param string $link            
     *
     * @return Link
     */
    public function setLink($link)
    {
        $this->link = $link;
        
        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set category
     *
     * @param string $category            
     *
     * @return Link
     */
    public function setCategory($category)
    {
        $this->category = $category;
        
        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set pic
     *
     * @param string $pic            
     *
     * @return Link
     */
    public function setPic($pic)
    {
        $this->pic = $pic;
        
        return $this;
    }

    /**
     * Get pic
     *
     * @return string
     */
    public function getPic()
    {
        return $this->pic;
    }

    /**
     * Set dateInsert
     *
     * @param \DateTime $date            
     *
     * @return Link
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
}
