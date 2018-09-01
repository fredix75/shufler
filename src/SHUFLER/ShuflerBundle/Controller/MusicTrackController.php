<?php
namespace SHUFLER\ShuflerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SHUFLER\ShuflerBundle\Entity\MusicTrack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class MusicTrackController extends Controller
{

    /**
     *  Tracks Index.
     *     *            
     * @return Rendering template
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getTracksAction()
    {      
        return $this->render('SHUFLERShuflerBundle:Music:tracks.html.twig', [
            'columns_db' => MusicTrack::TRACKS_COLUMNS_TO_DISPLAY_DB,
            'columns_dt' => MusicTrack::TRACKS_COLUMNS_TO_DISPLAY_DT
        ]);
    }

    /**
     * Get All Tracks.
     *
     * @return Rendering template
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getTracksSourceAction(Request $request)
    {
        $length = $request->get('length'); 
        $length = $length && ($length!=-1)?$length:0; 
  
        $start = $request->get('start'); 
        $start = $length?($start && ($start!=-1)?$start:0)/$length:0; 
  
        $search = $request->get('search'); 
        $filters = [ 
            'query' => @$search['value'] 
        ]; 
        
        $sort = $request->get('order')[0]['column'];
        $sort = MusicTrack::TRACKS_COLUMNS_TO_DISPLAY_DB[$sort];
        
        $dir = @$request->get('order')[0]['dir'];
  
        $tracks = $this->getDoctrine()->getRepository('SHUFLERShuflerBundle:MusicTrack')->getTracksAjax(
            $filters, $start, $length, $sort, $dir
        ); 
  
        $output = array( 
            'data' => array(), 
            'recordsFiltered' => count($this->getDoctrine()->getRepository('SHUFLERShuflerBundle:MusicTrack')->getTracksAjax($filters, 0, false)),
            'recordsTotal' => count($this->getDoctrine()->getRepository('SHUFLERShuflerBundle:MusicTrack')->getTracksAjax(array(), 0, false))
        ); 

        foreach ($tracks as $track) { 
            $output['data'][] = [ 
                'id' => $track->getId(), 
                'auteur' => $track->getAuteur(), 
                'titre' => $track->getTitre(),
                'numero' => $track->getNumero(),
                'album' => $track->getAlbum(),
                'annee' => $track->getAnnee(),
                'artiste' => $track->getArtiste(),
                'genre' => $track->getGenre(),
                'duree' => $track->getDuree(),
                'pays' => $track->getPays(),
                'bitrate' => $track->getBitrate(),
                'note' => $track->getNote(),
            ]; 
        } 
  
        return new Response(json_encode($output), 200, ['Content-Type' => 'application/json']); 
    }
   
    /**
     *  Albums Index.
     *     *
     * @return Rendering template
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getAlbumsAction()
    {
        return $this->render('SHUFLERShuflerBundle:Music:albums.html.twig', [
            'columns_db' => MusicTrack::ALBUMS_COLUMNS_TO_DISPLAY_DB,
            'columns_dt' => MusicTrack::ALBUMS_COLUMNS_TO_DISPLAY_DT
        ]);
    }
    
    /**
     * Get All Tracks.
     *
     * @return Rendering template
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getAlbumsSourceAction(Request $request)
    {
        $length = $request->get('length');
        $length = $length && ($length!=-1)?$length:0;
        
        $start = $request->get('start');
        $start = $length?($start && ($start!=-1)?$start:0)/$length:0;
        
        $search = $request->get('search');
        $filters = [
            'query' => @$search['value']
        ];
        
        $sort = $request->get('order')[0]['column'];
        $sort = MusicTrack::ALBUMS_COLUMNS_TO_DISPLAY_DB[$sort];
        
        $dir = @$request->get('order')[0]['dir'];
        
        $albums = $this->getDoctrine()->getRepository('SHUFLERShuflerBundle:MusicTrack')->getAlbumsAjax(
            $filters, $start, $length, $sort, $dir
            );
        
        $output = array(
            'data' => array(),
            'recordsFiltered' => count($this->getDoctrine()->getRepository('SHUFLERShuflerBundle:MusicTrack')->getAlbumsAjax($filters, 0, false)),
            'recordsTotal' => count($this->getDoctrine()->getRepository('SHUFLERShuflerBundle:MusicTrack')->getAlbumsAjax(array(), 0, false))
        );
        
        foreach ($albums as $album) {
            $output['data'][] = [
                'album' => '<a href="#" class="album_tracks" data-toggle="modal" data-target="#musicModal" data-artiste="' . $album->getArtiste() .'" data-album="' . $album->getAlbum() .'" ><span class="glyphicon glyphicon-chevron-right"></span></a> ' 
                    . $album->getAlbum(),
                'annee' => $album->getAnnee(),
                'artiste' => $album->getArtiste(),
                'genre' => $album->getGenre()
            ];
        }
        
        return new Response(json_encode($output), 200, ['Content-Type' => 'application/json']);
    }
    
    /**
     * Get Tracks by One Album.
     *
     * @return Rendering template
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getTracksByAlbumAction(Request $request)
    {
        $artiste = $request->get('artiste');
        $album = $request->get('album');
        $tracks = $this->getDoctrine()->getRepository('SHUFLERShuflerBundle:MusicTrack')->getTracksByAlbum($artiste, $album);      
        $output= [
            'data' => []
        ];
        foreach($tracks as $track) {
            $output['data'][] = [
                'numero' => $track->getNumero(),
                'titre' => $track->getTitre(),
                'auteur' => $track->getAuteur(),
                'duree' => $track->getDuree()
            ];
        }     
        return new Response(json_encode($output), 200, ['Content-Type' => 'application/json']);
    }
    
}