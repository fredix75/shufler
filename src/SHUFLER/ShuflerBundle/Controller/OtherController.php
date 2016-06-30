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
	
	public function rssAction(){
		//var_dump(simplexml_load_file('http://www.numerama.com/feed/'));
		
		error_reporting(0);
		$infos=array();

		$rss=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Flux')->getRSS();
		
		$fluxParser = $this->container->get('shufler.fluxParser');
		
		foreach($rss as $key){
			if($key->getName()=='Liberation'){
				$infos[$key->getName()]['flux']=$fluxParser->convertXML2($key->getUrl());
			}else{
				$infos[$key->getName()]['flux']=$fluxParser->convertXML($key->getUrl());
			}
			if($key->getLogo()!=null){
				$infos[$key->getName()]['pic']=$key->getPic();
			}else{
				$infos[$key->getName()]['pic']=null;
			}
		}
	
		

		//set_exception_handler(\Exception);
		//var_dump($infos);
		//return new Response('ok');
		
		return $this->render('SHUFLERShuflerBundle:Other:rss.html.twig',array('infos'=>$infos));
	}
	
	public function podcastAction(Request $request){	
		error_reporting(0);
		$infos=array();
		$fluxParser = $this->container->get('shufler.fluxParser');
		
		if ($request->isXmlHttpRequest()){
			$pod=$request->query->get('pod');
			
			$rss=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Flux')->getFlux($pod);
			$page=$request->query->get('page');
			$debut=($page-1)*6;
			

			$contenu=$fluxParser->convertXML($rss->getUrl());
			for($i=$debut;$i<$debut+6;$i++){
				$infos['flux'][]=$contenu[$i];
			}
				
			return new Response(json_encode($infos));
		}
		
		$rss=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Flux')->getPodcast();
					
		foreach($rss as $key){
			$infos[$key->getName()]['id']=$key->getId();
			$contenu=$fluxParser->convertXML($key->getUrl());
			for($i=0;$i<6;$i++){
				$infos[$key->getName()]['flux'][]=$contenu[$i];				
			}
			if($key->getLogo()!=null){
				$infos[$key->getName()]['pic']=$key->getPic();
			}else{
				$infos[$key->getName()]['pic']=null;
			}
			$infos[$key->getName()]['pages']=ceil(count($contenu)/6);
		}
		

		//set_exception_handler(\Exception);
		//var_dump(json_encode($infos));
		//return new Response('ok');
	
		return $this->render('SHUFLERShuflerBundle:Other:podcast.html.twig',array('infos'=>$infos));
	}
	
	public function fluxEditAction(Request $request,$id){
		$flux= new Flux();
		
		if($id!=0){
			$flux=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Flux')->getFlux($id);
		}
		
		$form=$this->createForm(new FluxType(),$flux);
		
		if( $form->handleRequest($request)->isValid()) {
						
			$em = $this->getDoctrine()->getManager();
			$em->persist($flux);
			$em->flush();
		
			$request->getSession()->getFlashBag()->add('notice', 'Flux bien enregistré.');
		
			return $this->redirect($this->generateUrl('shufler_shufler_flux_edit', array('id' => $flux->getId())));
		}
		
		return $this->render('SHUFLERShuflerBundle:Other:fluxEdit.html.twig',array(
				'form'=>$form->createView(),
				'flux'=>$flux
			)
		);
	}
	
    public function deleteAction(Flux $flux){
	    $em=$this->getDoctrine()->getManager();
     	$em->remove($flux);
     	$em->flush();
     	return $this->redirectToRoute('shufler_shufler_homepage');
    }
    
    public function deleteLogoAction($id){
	   	return $this->redirectToRoute('shufler_shufler_homepage');
    }
}