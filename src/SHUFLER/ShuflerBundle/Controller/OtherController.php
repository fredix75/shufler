<?php
namespace SHUFLER\ShuflerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SHUFLER\ShuflerBundle\Entity\Flux;
use Symfony\Component\HttpFoundation\Response;
use SHUFLER\ShuflerBundle\Entity\ChannelFlux;
use SHUFLER\ShuflerBundle\Form\ChannelFluxType;
use SHUFLER\ShuflerBundle\Entity\MusicTrack;
use SHUFLER\ShuflerBundle\Entity\Album;
use Symfony\Component\Config\Definition\Exception\Exception;

class OtherController extends Controller
{

    /**
     * Search API
     *
     * @param Request $request            
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchApiVideoAction(Request $request)
    {
        if ($request->get('search_api')) {
            $search = $request->get('search_api');
            
            // Vimeo
            $lib = new \Vimeo\Vimeo($this->getParameter('vimeo_id'), $this->getParameter('vimeo_secret'), $this->getParameter('vimeo_access_token'));
            
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

    public function searchApiChannelAction(Request $request)
    {
        $search = null;
        if ($request->get('search_api')) {
            $search = $request->get('search_api');
        }
        $idChannel = null;
        return $this->render('SHUFLERShuflerBundle:Other:channelsAPI.html.twig', array(
            'search' => $search,
            'idChannel' => $idChannel
        ));
    }

    /**
     * Edit Channel of Flux
     *
     * @param Request $request            
     * @return \Symfony\Component\HttpFoundation\Response @Security("has_role('ROLE_AUTEUR')")
     */
    public function channelEditAction(Request $request)
    {
        $channel = new ChannelFlux();
        
        $id = $request->get('id');
        
        $key = $request->get('channelkey');
        $providerName = $request->get('channelTypeName');
        
        $id_flux = @$request->get('id_flux');
        $type = @$request->get('type');
        
        $channel->setProviderId($key);
        $channel->setProviderName($providerName);
        
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
            'action' => $this->generateUrl('shufler_edit_channel', array(
                'type' => $type,
                'id' => $id
            )),
            'method' => 'POST'
        ));
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->get('imgNet')) {
                $channel->setImage($request->get('imgNet'));
                $channel->setOldImage($request->get('imgNet'));
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($channel);
            $em->flush();
            
            if (! $channel->isVideo()) {
                $response = new Response(json_encode([
                    'success' => true,
                    'id' => $channel->getId(),
                    'name' => $channel->getName()
                ]));
                
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            } else {
                $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Chaine bien enregistrée.');
                
                return $this->redirect($this->generateUrl('shufler_edit_channel', array(
                    'type' => 2,
                    'id' => $channel->getId()
                )));
            }
        }
        
        if ($type == 1) {
            return $this->render('SHUFLERShuflerBundle:Other:channelEdit_inc.html.twig', array(
                'form' => $form->createView(),
                'channel' => $channel,
                'type' => $type,
                'id_flux' => $id_flux
            ));
        } elseif ($type == 2) {
            return $this->render('SHUFLERShuflerBundle:Other:channelEdit.html.twig', array(
                'form' => $form->createView(),
                'channel' => $channel,
                'type' => $type
            ));
        }
    }

    public function getVideoChannelsAction()
    {
        $fluxChannels = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:ChannelFlux')
            ->getChannelFluxVideo();
        
        $videosPlaylists = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Flux')
            ->getPlayListsVideo();
        
        $channels = array_merge($fluxChannels, $videosPlaylists);
        
        shuffle($channels);
        
        return $this->render('SHUFLERShuflerBundle:Other:videoChannels.html.twig', array(
            'channels' => $channels
        ));
    }

    /**
     * Delete Video
     *
     * @param Flux $flux            
     * @return \Symfony\Component\HttpFoundation\RedirectResponse @Security("has_role('ROLE_AUTEUR')")
     */
    public function deleteAction(ChannelFlux $channel, $id_flux = null)
    {
        $isVideo = $channel->isVideo();
        $em = $this->getDoctrine()->getManager();
        try {
            if ($id_flux != 0) {
                $flux = $em->getRepository('SHUFLERShuflerBundle:Flux')->find($id_flux);
                $flux->setChannel();
            }
            $image = $channel->getOldImage();
            $em->remove($channel);
            $em->flush();
            if (! $isVideo) {
                $channel->deleteLogo($this->getParameter('logo_directory') . '/' . $image);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (is_callable([
                $e,
                "getErrorCode"
            ]) && $e->getErrorCode() === 1451) {
                $message = "Ce channel est utilisé par d'autres podcasts. Opération impossible en l'état actuel de la situation.
                    Contactez vos parents au plus vite car les choses peuvent prendre une sale tournure mon gaillard.
                    Que je ne vous y reprenne plus jamais.";
            }
            $this->get('session')
                ->getFlashBag()
                ->add('danger', $message);
            if (! $isVideo) {
                return $this->redirectToRoute('shufler_edit_channels', array(
                    'id' => $channel->getId()
                ));
            }
        }
        
        $message = "Channel supprimé... sans vergogne ni pitié.";
        
        $this->get('session')
            ->getFlashBag()
            ->add('success', $message);
        
        if (! $isVideo) {
            return $this->redirect($this->generateUrl('shufler_flux_edit', array(
                'id' => $id_flux
            )));
        }
        return $this->redirect($this->generateUrl('shufler_video_channels'));
    }

    /**
     * Delete Image
     *
     * @param ChannelFlux $channel            
     * @return \Symfony\Component\HttpFoundation\RedirectResponse @Security("has_role('ROLE_AUTEUR')")
     */
    public function deleteLogoAction(ChannelFlux $channel)
    {
        try {
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
        
        return $this->redirectToRoute('shufler_podcast');
    }

    /**
     * Aspicture
     *
     * @param Request $request            
     * @return \Symfony\Component\HttpFoundation\Response @Security("has_role('ROLE_ADMIN')")
     */
    public function aspictureAction(Request $request)
    {
        $state = 0;
        if ($request->get('aspicture_name')) {
            $id = $request->get('aspicture_id');
            $name = $this->get('util.twig_extension')->stripAccentsFilter(($request->get('aspicture_name')));
            if (! is_dir($this->getParameter('bd_directory') . '/' . $name)) {
                rename($this->getParameter('bd_directory') . '/' . $id, $this->getParameter('bd_directory') . '/' . $name);
            } else {
                $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'Nommage impossible, le dossier existe déja');
            }
        }
        if ($request->get('aspicture_url')) {
            $url = $request->get('aspicture_url');
            try {
                $id = array_reverse(explode('/', explode('?', $url)[0]))[0];
                $sign = explode('sign=', $url)[1];
                $root = 'https://reader.izneo.com/read/';
                
                if (! is_dir($this->getParameter('bd_directory')))
                    mkdir($this->getParameter('bd_directory'));
                $targetDirectory = $this->getParameter('bd_directory') . '/' . $id;
                if (! is_dir($targetDirectory))
                    mkdir($targetDirectory);
                $files = scandir($targetDirectory);
                
                rsort($files, SORT_STRING);
                $lastKey = ($files[0] != '.' && $files[0] != '..') ? explode('.jpg', $files[0])[0] * 1 : - 1;
                
                $key = $lastKey + 1;
                
                while (get_headers($root . $id . '/' . $key . '?login=cvs&sign=' . $sign)[0] == 'HTTP/1.1 200 OK') {
                    copy($root . $id . '/' . $key . '?login=cvs&sign=' . $sign, $this->getParameter('bd_directory') . '/' . $id . '/' . sprintf("%04d", $key) . '.jpg');
                    $key ++;
                }
                $state = $id;
            } catch (\Exception $e) {
                $state = - 1;
                $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'Problème d\'URL ou quelque chose du genre. Bref: mauvais délire');
            }
        }
        return $this->render('SHUFLERShuflerBundle:Other:aspicture.html.twig', array(
            'state' => $state
        ));
    }

    /**
     * maps in progress ...
     */
    public function mapAction()
    {
        return $this->render('SHUFLERShuflerBundle:Other:map.html.twig');
    }

    /**
     * TEST ZONE
     */
    public function testAction()
    {}
}