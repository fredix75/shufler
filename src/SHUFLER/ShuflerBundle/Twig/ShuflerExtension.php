<?php
namespace SHUFLER\ShuflerBundle\Twig;

class ShuflerExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('categorieDisplay', array($this, 'categoryFilter')),
			new \Twig_SimpleFilter('genreDisplay', array($this, 'genreFilter')),
			new \Twig_SimpleFilter('yearDisplay', array($this, 'yearFilter')),
       		new \Twig_SimpleFilter('jsonDecode', array($this, 'jsonDecodeFilter')),
        );
    }

    public function categoryFilter($categorie)
    {
        switch($categorie){
			case 1:
				$category='Anim';
				break;
			case 2:
				$category='Music';
				break;
			case 3:
				$category='etrange';
				break;
			case 4:
				$category='parodie';
				break;
			case 6:
				$category='tv';
				break;
			case 8:
				$category='ciné';
				break;
			case 9:
				$category='nature/timelapse';
				break;
			default:
				$category='autre';
		}
        return $category;
    }
	
	public function genreFilter($genre){
		switch($genre){
    		case 1:
    			$genreDisplay='Jazz/Blues';
    			break;
   			case 2:
   				$genreDisplay='Rock n\'Roll';
    			break;
    		case 3:
    			$genreDisplay='Rock/Pop';
    			break;
    		case 4:
    			$genreDisplay='Soul/Funk';
    			break;
    		case 5:
    			$genreDisplay='Reggae/Ragga/Dub';
    			break;
    		case 6:
    			$genreDisplay='Chanson';
    			break;
    		case 7:
    			$genreDisplay='Punk/Alternatif';
    			break;
    		case 8:
    			$genreDisplay='Hard-Rock';
    			break;
    		case 9:
    			$genreDisplay='Blues Rock';
    			break;
    		case 10:
    			$genreDisplay='Electro/DJs';
    			break;
    		case 11:
    			$genreDisplay='World';
    			break;
    		case 12:
    			$genreDisplay='Roots/Folk Rock';
    			break;
    		case 13:
    			$genreDisplay='Rock progressif/psyché';
    			break;
    		case 14:
    			$genreDisplay='Variétés / Pop';
    			break;
    		case 15:
    			$genreDisplay='Trip-Hop';
    			break;
    		case 16:
    			$genreDisplay='Afrobeat';
    			break;
    		default:
    			$genreDisplay='Autre';
    	}
		
		return $genreDisplay;
	}
	
	public function yearFilter($annee){
		if($annee!=-1 && $annee!=null && $annee!=0){
    		return $annee;
    	}
   		return;  
	}
	
	public function jsonDecodeFilter($json)
	{
		return json_decode($json, true);
	}
		
    public function getName()
    {
        return 'shufler_extension';
    }
}
