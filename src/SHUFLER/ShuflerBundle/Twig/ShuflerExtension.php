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
       		new \Twig_SimpleFilter('convertFrame', array($this, 'convertFrameFilter')),
       		new \Twig_SimpleFilter('popUp', array($this, 'popUpFilter')),
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
	
	public function convertFrameFilter($lien){
		$width='100%';
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
		
		}else{
			$frame="<img class='embed-responsive-item' src='http://s3.amazonaws.com/colorcombos-images/users/1/color-schemes/color-scheme-2-main.png?v=20111009081033' width=".$width." />";
		}
		return $frame;
	}
	
	public function popUpFilter($lien){
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
		
    public function getName()
    {
        return 'shufler_extension';
    }
}
