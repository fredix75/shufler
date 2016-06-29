<?php

namespace SHUFLER\ShuflerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller {

		
	public function navigationAction(){
		 
		$categories=array(1=>"Anim'",2=>"Music Moment",3=> "Strange",4=>"Funny",6=>"Seen on Tv",8=>"Movie Time",9=>"Nature");
		$periodes=array("2016-2030","2001-2015","1986-2000","1971-1985","1956-1970","1940-1955","<1939");
		 
		return $this->render('SHUFLERShuflerBundle:Main:nav.html.twig', array('categories'=>$categories,'periodes'=>$periodes));
	}
	
	public function searchAjaxAction(Request $request){
	
		if ($request->isXmlHttpRequest()){
			$search=$request->query->get('query');
			$videos=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Video')->searchAjax($search);
			$suggestions=array();
			if($videos){
				foreach($videos as $video){
					$suggestions['suggestions'][]=$video;
				}
			}else{
				$suggestions['suggestions']=array();
			}
			
			return new Response(json_encode($suggestions));

		}
		
	}
	
	
	public function testAction(){
	
			$videos=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Video')->searchAjax('sim');
				
			foreach($videos as $video){

			}
			return new Response('ok');
		
	}
	
	public function mailAction(){
/*		
		//mail('fred_ric@hotmail.com', 'test' , 'test', 'from : fred_ric@hotmail.com');
		
*/
		

		
		$key='AIzaSyBNgi3EddUNsFThnFQpPt3RR9kkuWmhHdo';
		$videoid='IM0ls5rQePc';
		
		//$url='https://www.googleapis.com/youtube/v3/videos?id='.$videoid.'&key='.$key.'&fields=items&part=contentDetails,statistics';
		
		$url='https://www.googleapis.com/youtube/v3/search?part=snippet&q=brel&maxResults=50&key='.$key;
		
		$content=file_get_contents($url);
		$content=json_decode($content);
		$arrayVideos=array();
		$liste="";
		//var_dump($content->{'items'}[0]->{'snippet'});
		foreach($content->{'items'} as $item){
			if(isset($item->{'snippet'}->{'thumbnails'}->{'default'}->{'url'})){
				//$liste.=" ".$item->{'snippet'}->{'thumbnails'};
				$arrayVideos[]=$item->{'snippet'}->{'thumbnails'}->{'default'}->{'url'};
			}
		}
		
		//$cR=$this->container->get('request_stack')->getCurrentRequest();
		//var_dump($cR);
		//$title= $content->{'items'}[0]->{'snippet'}->{'title'};
		return $this->render('SHUFLERShuflerBundle:Main:blank.html.twig',array(
				'content'=>$arrayVideos,
				'liste' =>$liste
		));
		
		
		
	}
}