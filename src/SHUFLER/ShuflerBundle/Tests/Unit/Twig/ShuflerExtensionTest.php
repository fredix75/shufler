<?php
namespace SHUFLER\ShuflerBundle\Tests\Unit\Twig;

use SHUFLER\ShuflerBundle\Twig\ShuflerExtension;

class ShuflerExtensionTest extends \PHPUnit_Framework_TestCase
{

    private $filter;

    public function setUp()
    {
        $this->filter = new ShuflerExtension();
    }

    public function testGetCategory()
    {
        $cat = 2;
        
        $categorie = $this->filter->categoryFilter($cat);
        
        $this->assertEquals('Music', $categorie);
    }

    public function testUnknownYear()
    {
        $y = - 1;
        
        $year = $this->filter->yearFilter($y);
        
        $this->assertNull($year);
    }
}
