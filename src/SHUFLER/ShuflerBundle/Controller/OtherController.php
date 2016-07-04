<?php

namespace SHUFLER\ShuflerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SHUFLER\ShuflerBundle\Entity\Link;
use SHUFLER\ShuflerBundle\Entity\Flux;
use SHUFLER\ShuflerBundle\Form\FluxType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class OtherController extends Controller {
	

	/**
	 * @Security("has_role('ROLE_AUTEUR')")
	 */
	public function linksAction(){
		$links=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Link')->getLinks();
		$categories=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Link')->getCategories();
		return $this->render('SHUFLERShuflerBundle:Other:links.html.twig', array('links'=>$links,'categories'=>$categories));
		 
	}
	
	public function searchApiAction(Request $request){
	
		if($request->get('search_api')){
			$search=$request->get('search_api');
			 
			
			// Vimeo
			$client_id='d39d579aedf22d12315f374a456d0656b23c7b40';
			$client_secret='Ed9/U8ZIOcdP+aO2if/jYC4YMJbl811TeoGZIRNzalRG498hn/VA3jKrxsCytn01kV8rIgwIfa6rLrkdYSsPvaSXmy9R93ikhGCN31mtylcqCKKtXkYPmdw737sU0oEA';
			$token_access='bc14d9b5371d9669ecdd1e3027572db1';
			 
			$lib = new \Vimeo\Vimeo($client_id, $client_secret, $token_access);
			 
			$content=$lib->request('/videos',array('query'=>$search, 'per_page'=>20, 'page'=>1));
			 
			$resultatVIM=array();
	
			$i=0;
			foreach($content['body']['data'] as $item){
				$resultatVIM[$i]['link']=$item['pictures']['sizes'][1]['link'];
				$resultatVIM[$i]['name']=$item['name'];
				$resultatVIM[$i]['url']=str_replace('https://', '//player.', $item['link']);
				$resultatVIM[$i]['author']=$item['user']['name'];
				$resultatVIM[$i]['date']= date("d-m-Y",strtotime($item['created_time']));
				$i++;
			}
	
			//Wikipedia
			/*
			$urlWiki='https://fr.wikipedia.org/w/api.php?action=query&formatversion=2&generator=prefixsearch&gpssearch='.$search.'&gpslimit=10&prop=pageimages|pageterms&piprop=thumbnail&pithumbsize=50&pilimit=10&redirects=&wbptterms=description&format=json';
			 
			$contentWiki=file_get_contents($urlWiki);
	
			$contentWiki=json_decode($contentWiki);
			$wiki=array();
			if($contentWiki){
				for( $i=1;$i<=count($contentWiki->{'query'}->{'pages'});$i++){
					if(isset($contentWiki->{'query'}->{'pages'}[$i]->{'thumbnail'})){
						$wiki[]['image'] = $contentWiki->{'query'}->{'pages'}[$i]->{'thumbnail'}->{'source'};
					}
					if(isset($contentWiki->{'query'}->{'pages'}[$i]->{'title'})){
						$wiki[]['title'] = $contentWiki->{'query'}->{'pages'}[$i]->{'title'};
					}
				}
			}
			*/
			$wiki=null;
			if($request->get('id_video')){
				$idVideo=$request->get('id_video');
			}else{
				$idVideo=null;
			}
			
		}else{
			$search=null;
			$resultatVIM=null;
			$idVideo=null;
			$wiki=null;
		}
				
		return $this->render('SHUFLERShuflerBundle:Other:videosAPI.html.twig',array(
				'search'=>$search,
				'resultatVIM'=>$resultatVIM,
				'idVideo'=> $idVideo,
				'wiki' => $wiki
		));
	
	}
	
	
}