<?php
namespace SHUFLER\ShuflerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SHUFLER\ShuflerBundle\Entity\Flux;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use SHUFLER\ShuflerBundle\Entity\ChannelFlux;
use SHUFLER\ShuflerBundle\Form\ChannelFluxType;

class OtherController extends Controller
{


    /**
     * Get Categories
     * 
     * @return array
     */
    private function getCategoriesLinks() {
        return $this->getDoctrine()
        ->getManager()
        ->getRepository('SHUFLERShuflerBundle:Link')
        ->getCategories();
    }
    
    
    /**
     * Search API
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchApiAction(Request $request)
    {
        if ($request->get('search_api')) {
            $search = $request->get('search_api');
            
            // Vimeo
            $client_id = 'd39d579aedf22d12315f374a456d0656b23c7b40';
            $client_secret = 'Ed9/U8ZIOcdP+aO2if/jYC4YMJbl811TeoGZIRNzalRG498hn/VA3jKrxsCytn01kV8rIgwIfa6rLrkdYSsPvaSXmy9R93ikhGCN31mtylcqCKKtXkYPmdw737sU0oEA';
            $token_access = 'bc14d9b5371d9669ecdd1e3027572db1';
            
            $lib = new \Vimeo\Vimeo($client_id, $client_secret, $token_access);
            
            $content = $lib->request('/videos', array(
                'query' => $search,
                'per_page' => 20,
                'page' => 1
            ));
            
            $resultatVIM = array();
            
            $i = 0;
            foreach ($content['body']['data'] as $item) {
                $resultatVIM[$i]['link'] = $item['pictures']['sizes'][1]['link'];
                $resultatVIM[$i]['name'] = $item['name'];
                $resultatVIM[$i]['url'] = str_replace('https://', '//player.', $item['link']);
                $resultatVIM[$i]['author'] = $item['user']['name'];
                $resultatVIM[$i]['date'] = date("d-m-Y", strtotime($item['created_time']));
                $i ++;
            }
            
            // Wikipedia
            /*
             * $urlWiki='https://fr.wikipedia.org/w/api.php?action=query&formatversion=2&generator=prefixsearch&gpssearch='.$search.'&gpslimit=10&prop=pageimages|pageterms&piprop=thumbnail&pithumbsize=50&pilimit=10&redirects=&wbptterms=description&format=json';
             *
             * $contentWiki=file_get_contents($urlWiki);
             *
             * $contentWiki=json_decode($contentWiki);
             * $wiki=array();
             * if($contentWiki){
             * for( $i=1;$i<=count($contentWiki->{'query'}->{'pages'});$i++){
             * if(isset($contentWiki->{'query'}->{'pages'}[$i]->{'thumbnail'})){
             * $wiki[]['image'] = $contentWiki->{'query'}->{'pages'}[$i]->{'thumbnail'}->{'source'};
             * }
             * if(isset($contentWiki->{'query'}->{'pages'}[$i]->{'title'})){
             * $wiki[]['title'] = $contentWiki->{'query'}->{'pages'}[$i]->{'title'};
             * }
             * }
             * }
             */
            $wiki = null;
            if ($request->get('id_video')) {
                $idVideo = $request->get('id_video');
            } else {
                $idVideo = null;
            }
        } else {
            $search = null;
            $resultatVIM = null;
            $idVideo = null;
            $wiki = null;
        }
        
        return $this->render('SHUFLERShuflerBundle:Other:videosAPI.html.twig', array(
            'search' => $search,
            'resultatVIM' => $resultatVIM,
            'idVideo' => $idVideo,
            'wiki' => $wiki
        ));
    }

    /**
     * Edit Channel of Flux
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function channelEditAction(Request $request)
    {
        
        $channel = new ChannelFlux();
        
        $id =  $request->get('id');
        
        if ($id != 0) {
            try {
                $channel = $this->getDoctrine()
                ->getManager()
                ->getRepository('SHUFLERShuflerBundle:ChannelFlux')
                ->find($id);
                
            } catch (\Exception $e) {
                $this->get('session')
                ->getFlashBag()
                ->add('danger', $e->getMessage());
                return $this->redirectToRoute('shufler_flux_edit');
            }
        }
        
        $form = $this->createForm(ChannelFluxType::Class, $channel, array(
            'action' => $this->generateUrl('shufler_edit_channel') . '?id=' . $id ,
            'method' => 'POST'
        ));
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($channel);
            $em->flush();
            
            $response = new Response(json_encode([
                'success' => true,
                'id' => $channel->getId(),
                'name' => $channel->getName()
            ]));
            
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        }
        
        return $this->render('SHUFLERShuflerBundle:Other:channelEdit.html.twig', array(
            'form' => $form->createView(),
            'channel' => $channel
        ));
    }
    
    /**
     * Delete Video
     *
     * @param Flux $flux
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     *  @Security("has_role('ROLE_AUTEUR')")
     */
    public function deleteAction(ChannelFlux $channel)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $image = $channel->getOldImage();
            $em->remove($channel);
            $em->flush();
            $channel->deleteLogo($this->getParameter('logo_directory') . '/' . $image);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if ($e->getErrorCode() === 1451) {
                $message =  "Ce channel est utilisé par d'autres podcasts. Opération impossible en l'état actuel de la situation.
                    Contactez vos parents au plus vite car les choses peuent prendre une sale tournure mon gaillard.
                    Que je ne vous y reprenne plus jamais.";
            }
            $this->get('session')
            ->getFlashBag()
            ->add('danger', $message);
            return $this->redirectToRoute('shufler_podcast');
        }
    }
    
    /**
     * Delete Image
     *
     * @param ChannelFlux $channel
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     *  @Security("has_role('ROLE_AUTEUR')")
     */
    public function deleteLogoAction(ChannelFlux $channel)
    {
        try{
            $image = $channel->getOldImage();
            $em = $this->getDoctrine()->getManager();
            $channel->setOldImage();
            $channel->setImage();
            $em->flush();
            $channel->deleteLogo($this->getParameter('logo_directory') . '/' . $image);
        } catch (\Exception $e) {
            $this->get('session')
            ->getFlashBag()
            ->add('danger', $e->getMessage());
        }
        
        return $this->redirecToRoute('shufler_podcast');
    }
    
    /**
     * 
     * TEST ZONE
     * 
     */
    public function testAction()
    {
        $tweets = array();
        return $this->render('SHUFLERShuflerBundle:Other:test.html.twig', array(
            'tweets' => $tweets
        ));
    }
}