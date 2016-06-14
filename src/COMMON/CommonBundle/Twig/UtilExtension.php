<?php
namespace COMMON\CommonBundle\Twig;

class UtilExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('dateFr', array($this, 'dateFrFilter')),
       		new \Twig_SimpleFilter('dateFlux', array($this, 'dateFluxFilter')),
       		new \Twig_SimpleFilter('dateFlux2', array($this, 'dateFlux2Filter')),
       		new \Twig_SimpleFilter('imageSubs', array($this, 'imageSubsFilter')),
        );
    }
	
	public function dateFrFilter($annee){
		if($annee!=-1 && $annee!=null && $annee!=0){
    		return $annee;
    	}
   		return;  
	}
	
	public function dateFluxFilter($dateRaw){
		$d = \DateTime::createFromFormat("D, d M Y H:i:s T", $dateRaw);
		if($d){
			$date=$d->format('d M Y H:i:s');
   			return $date;  
		}
		return $dateRaw;
	}
	
	
	public function dateFlux2Filter($dateRaw){
		$d = \DateTime::createFromFormat("Y-m-d?H:i:sT", $dateRaw);
		if($d){
			$date=$d->format('d M Y H:i:s');
   			return $date;  
		}
		return $dateRaw;
	}
	
	public function imageSubsFilter($url){
		$pos=strpos($url,'?');
		$image=substr($url,0,$pos);
		return $image;
	}
	
    public function getName()
    {
        return 'util_extension';
    }
}
