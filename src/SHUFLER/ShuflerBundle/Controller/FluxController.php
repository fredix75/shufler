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

class FluxController extends Controller {
	
	
	//test de commentaire parfaitement inutile
	
	//merci de votre attention
	
	//bisous
	
	public function rssAction(Request $request){
	
		//error_reporting(0);
		$infos=array();
		$fluxParser = $this->container->get('shufler.fluxParser');
	
		if ($request->isXmlHttpRequest()){
			$id=$request->query->get('pod');
				
			$rss=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Flux')->getFlux($id);
			$page=$request->query->get('page');
			$debut=($page-1)*6;
	
			$contenu=$fluxParser->convertXML($rss->getUrl());
		
			for($i=$debut;$i<$debut+6;$i++){
				if(isset($contenu[$i])){
					$contenu[$i]->title=stripslashes($contenu[$i]->title);
					$contenu[$i]->description=stripslashes($contenu[$i]->description);

					$infos[]=$contenu[$i];
				}
			}
	
			return new Response(json_encode($infos));
		}
	
		$rss=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Flux')->getRss();
				
		foreach($rss as $flux){
			
			if($flux->getName()=='Liberation'){
				$libe[$flux->getId()]['flux']=$fluxParser->convertXML2($flux->getUrl());
				$libe[$flux->getId()]['name']=$flux->getName();
				$libe[$flux->getId()]['pic']=$flux->getPic();
			}else{
				$infos[$flux->getId()]['id']=$flux->getId();
				$jsonKeys[]=$flux->getId();
				$contenu=$fluxParser->convertXML($flux->getUrl());
				$infos[$flux->getId()]['name']=$flux->getName();
				if($flux->getLogo()!=null){
					$infos[$flux->getId()]['pic']=$flux->getPic();
				}else{
					$infos[$flux->getId()]['pic']=null;
				}
				$infos[$flux->getId()]['pages']=ceil(count($contenu)/6);
			}	

		}
	
		shuffle($infos);
		
		return $this->render('SHUFLERShuflerBundle:Flux:rss.html.twig',array('infos'=>$infos,'libe'=>$libe, 'jsonKeys'=>json_encode($jsonKeys)));
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
				$infos[]=$contenu[$i];
			}
	
			return new Response(json_encode($infos));
		}
	
		$rss=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Flux')->getPodcast();
			
		$jsonKeys = array();
	
		foreach($rss as $key){
			$infos[$key->getName()]['id']=$key->getId();
			$jsonKeys[]=$key->getId();
			$contenu=$fluxParser->convertXML($key->getUrl());
			$infos[$key->getId()]['name']=$key->getName();
			if($key->getLogo()!=null){
				$infos[$key->getId()]['pic']=$key->getPic();
			}else{
				$infos[$key->getId()]['pic']=null;
			}
			$infos[$key->getId()]['pages']=ceil(count($contenu)/6);
		}
	
		return $this->render('SHUFLERShuflerBundle:Flux:podcast.html.twig',array('infos'=>$infos, 'jsonKeys'=>json_encode($jsonKeys)));
	
	}
	
	public function radioAction(Request $request){
		error_reporting(0);
		$infos=array();
	
		$radios=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Flux')->getRadios();
	
		return $this->render('SHUFLERShuflerBundle:Flux:radios.html.twig',array('radios'=>$radios));
	
	}
	
	public function dailyPodAction(Request $request){
		error_reporting(0);
		$infos=array();
		$fluxParser = $this->container->get('shufler.fluxParser');
		
		$rss=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Flux')->getPodcast();
				
		foreach($rss as $key){
			$infos[$key->getName()]['id']=$key->getId();
			$contenu=$fluxParser->convertXML($key->getUrl());
			$infos[$key->getName()]['contenu']=$contenu[0];
			
			if($key->getLogo()!=null){
				$infos[$key->getName()]['pic']=$key->getPic();
			}else{
				$infos[$key->getName()]['pic']=null;
			}
		}
		
		return $this->render('SHUFLERShuflerBundle:Flux:dailyPod.html.twig',array('infos'=>$infos));
	}
	
	
	/**
	 * @Security("has_role('ROLE_AUTEUR')")
	 */
	public function fluxEditAction(Request $request,$id){
		$flux= new Flux();
	

		
		if($id!=0){
			try{
				$flux=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Flux')->getFlux($id);
			}catch(\Exception $e){
				$this->get('session')->getFlashBag()->add('danger',$e->getMessage());
				return $this->redirect($this->generateUrl('shufler_shufler_rss'));
			}
		}
	
		$form=$this->createForm(new FluxType(),$flux);
	
		if( $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($flux);
			$em->flush();
	
			$request->getSession()->getFlashBag()->add('success', 'Flux bien enregistré.');
	
			return $this->redirect($this->generateUrl('shufler_shufler_flux_edit', array('id' => $flux->getId())));
		}
		
		return $this->render('SHUFLERShuflerBundle:Flux:fluxEdit.html.twig',array(
				'form'=>$form->createView(),
				'flux'=>$flux
				)
		);
	}
	/**
     * @Security("has_role('ROLE_AUTEUR')")
     */
	public function deleteAction(Flux $flux){
		$em=$this->getDoctrine()->getManager();
		try{
			$em->remove($flux);
			$em->flush();
			return $this->redirectToRoute('shufler_shufler_homepage');
		}catch(\Exception $e){
			$this->get('session')->getFlashBag()->add('danger',$e->getMessage());
			return $this->redirectToRoute('shufler_shufler_rss');
		}

	}
	
	
	/**
	 * @Security("has_role('ROLE_AUTEUR')")
	 */
	public function deleteLogoAction($id){
		return $this->redirectToRoute('shufler_shufler_homepage');
	}
}