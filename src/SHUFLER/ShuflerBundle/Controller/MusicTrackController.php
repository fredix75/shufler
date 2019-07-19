<?php
namespace SHUFLER\ShuflerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SHUFLER\ShuflerBundle\Entity\MusicTrack;
use Symfony\Component\HttpFoundation\Response;

class MusicTrackController extends Controller
{

    /**
     * Tracks Index.
     * *
     *
     * @return Rendering template
     *         @Security("has_role('ROLE_ADMIN')")
     */
    public function getTracksAction(Request $request, $mode = "tracks")
    {
        if ($mode === 'tracks') {
            $columns_to_display_db = MusicTrack::TRACKS_COLUMNS_TO_DISPLAY_DB;
            $columns_to_display_dt = MusicTrack::TRACKS_COLUMNS_TO_DISPLAY_DT;
        } elseif ($mode === 'albums') {
            $columns_to_display_db = MusicTrack::ALBUMS_COLUMNS_TO_DISPLAY_DB;
            $columns_to_display_dt = MusicTrack::ALBUMS_COLUMNS_TO_DISPLAY_DT;
        } else {
            return;
        }
        
        if ($request->isXmlHttpRequest()) {
            
            $length = $request->get('length');
            $length = $length && ($length != - 1) ? $length : 0;
            
            $start = $request->get('start');
            $start = $length ? ($start && ($start != - 1) ? $start : 0) / $length : 0;
            
            $search = $request->get('search');
            $filters = [
                'query' => @$search['value']
            ];
            
            $sort = $request->get('order')[0]['column'];
            $sort = $columns_to_display_db[$sort];
            
            $dir = @$request->get('order')[0]['dir'];
            
            if ($mode === 'tracks') {
                
                $tracks = $this->getDoctrine()
                    ->getRepository('SHUFLERShuflerBundle:MusicTrack')
                    ->getTracksAjax($filters, $start, $length, $sort, $dir);
                
                $output = array(
                    'data' => array(),
                    'recordsFiltered' => count($this->getDoctrine()
                        ->getRepository('SHUFLERShuflerBundle:MusicTrack')
                        ->getTracksAjax($filters, 0, false)),
                    'recordsTotal' => count($this->getDoctrine()
                        ->getRepository('SHUFLERShuflerBundle:MusicTrack')
                        ->getTracksAjax(array(), 0, false))
                );
                foreach ($tracks as $track) {
                    $output['data'][] = [
                        'id' => $track->getId(),
                        'auteur' => strtoupper($track->getAuteur()) !== 'DIVERS' ? '<a href="#" class="artiste_track" data-toggle="modal" data-target="#musicModal" data-artiste="' . $track->getAuteur() . '" ><span class="glyphicon glyphicon-chevron-right"></span></a> ' . $track->getAuteur() : $track->getAuteur(),
                        'titre' => $track->getTitre(),
                        'numero' => $track->getNumero(),
                        'album' => '<a href="#" class="album_tracks" data-toggle="modal" data-target="#musicModal" data-artiste="' . $track->getArtiste() . '" data-album="' . $track->getAlbum() . '" ><span class="glyphicon glyphicon-chevron-right"></span></a> ' . $track->getAlbum(),
                        'annee' => $track->getAnnee(),
                        'artiste' => strtoupper($track->getArtiste()) !== 'DIVERS' ? '<a href="#" class="artiste_track" data-toggle="modal" data-target="#musicModal" data-artiste="' . $track->getArtiste() . '" ><span class="glyphicon glyphicon-chevron-right"></span></a> ' . $track->getArtiste() : $track->getArtiste(),
                        'genre' => $track->getGenre(),
                        'duree' => $track->getDuree(),
                        'pays' => $track->getPays(),
                        'bitrate' => $track->getBitrate(),
                        'note' => $track->getNote()
                    ];
                }
            } elseif ($mode === 'albums') {
                
                $albums = $this->getDoctrine()
                    ->getRepository('SHUFLERShuflerBundle:MusicTrack')
                    ->getAlbumsAjax($filters, $start, $length, $sort, $dir);
                
                $output = array(
                    'data' => array(),
                    'recordsFiltered' => count($this->getDoctrine()
                        ->getRepository('SHUFLERShuflerBundle:MusicTrack')
                        ->getAlbumsAjax($filters, 0, false)),
                    'recordsTotal' => count($this->getDoctrine()
                        ->getRepository('SHUFLERShuflerBundle:MusicTrack')
                        ->getAlbumsAjax(array(), 0, false))
                );
                
                foreach ($albums as $album) {
                    $output['data'][] = [
                        'album' => '<a href="#" class="album_tracks" data-toggle="modal" data-target="#musicModal" data-artiste="' . $album->getArtiste() . '" data-album="' . $album->getAlbum() . '" ><span class="glyphicon glyphicon-chevron-right"></span></a> ' . $album->getAlbum(),
                        'annee' => $album->getAnnee(),
                        'artiste' => strtoupper($album->getArtiste()) !== 'DIVERS' ? '<a href="#" class="artiste_track" data-toggle="modal" data-target="#musicModal" data-artiste="' . $album->getArtiste() . '" ><span class="glyphicon glyphicon-chevron-right"></span></a> ' . $album->getArtiste() : $album->getArtiste(),
                        'genre' => $album->getGenre()
                    ];
                }
            }
            return new Response(json_encode($output), 200, [
                'Content-Type' => 'application/json'
            ]);
        }
        
        return $this->render('SHUFLERShuflerBundle:Music:music.html.twig', [
            'display_mode' => $mode,
            'columns_db' => $columns_to_display_db,
            'columns_dt' => $columns_to_display_dt
        ]);
    }

    /**
     * Get Tracks by One Album.
     *
     * @return Rendering template
     *         @Security("has_role('ROLE_ADMIN')")
     */
    public function getTracksByAlbumAction(Request $request)
    {
        $artiste = $request->get('artiste');
        $album = $request->get('album');
        $tracks = $this->getDoctrine()
            ->getRepository('SHUFLERShuflerBundle:MusicTrack')
            ->getTracksByAlbum($artiste, $album);
        $output = [
            'data' => []
        ];
        foreach ($tracks as $track) {
            $output['data'][] = [
                'numero' => $track->getNumero(),
                'titre' => $track->getTitre(),
                'auteur' => $track->getAuteur(),
                'duree' => $track->getDuree(),
                'annee' => $track->getAnnee()
            ];
        }
        return new Response(json_encode($output), 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     *  Get Albums Random View
     *   
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function albumsApiAction()
    {
        $em = $this->getDoctrine()->getManager();
        $albums = $em->getRepository('SHUFLERShuflerBundle:Album')->getAlbums('youtube');
        
        shuffle($albums);
        
        $liste = [];
        foreach ($albums as $key => $album) {
            if ($key > 19)
                break;
            $liste[$album->getId()] = $album->getYoutubeKey();
        }
        
        return $this->render('SHUFLERShuflerBundle:Music:albumsApi.html.twig', array(
            'liste' => $liste
        ));
    }

    /**
     * Get Artistes Random View
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function artistesApiAction()
    {
        $em = $this->getDoctrine()->getManager();
        $artistes = $em->getRepository('SHUFLERShuflerBundle:Artiste')->getArtistes('youtube');
        
        shuffle($artistes);
        
        $liste = [];
        foreach ($artistes as $key => $artiste) {
            if ($key >= 500)
                break;
            
            $liste[] = $artiste;
        }
        
        return $this->render('SHUFLERShuflerBundle:Music:artistesApi.html.twig', array(
            'liste' => $liste
        ));
    }
    
    public function bestTracksAction() {
        $em = $this->getDoctrine()->getManager();
        $tracks = $em->getRepository('SHUFLERShuflerBundle:MusicTrack')->getTracksByRating(5);
        
        shuffle($tracks);
        
        $liste = "";
        foreach ($tracks as $key => $track) {
            if ($key == 1) {
                $single = $track->getYoutubeKey();
                continue;
            }
            if ($key > 100)
                break;
                
            $liste .= $track->getYoutubeKey() . ',';
        }
        
        return $this->render('SHUFLERShuflerBundle:Music:bestTracks.html.twig', array(
            'single' => $single,
            'liste' => $liste
        ));
    }
}