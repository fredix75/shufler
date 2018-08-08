<?php
namespace SHUFLER\ShuflerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SHUFLER\ShuflerBundle\Entity\Video;
use SHUFLER\ShuflerBundle\Form\VideoType;
use Symfony\Component\HttpFoundation\Response;

class VideoController extends Controller
{

    /**
     * Get & Mix List of Videos of Index
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $videos = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Video')
            ->getRandomVids();
        
        $anims = array();
        $musics = array();
        $etranges = array();
        
        $i = 0;
        foreach ($videos as $key => $video) {
            if (($video->getCategorie() == 1 || $video->getCategorie() == 9) && count($anims) < Video::INDEX_MAX_ANIM) {
                array_push($anims, $video);
                unset($videos[$key]);
            } elseif ($video->getCategorie() == 2 && count($musics) < Video::INDEX_MAX_MUSIC) {
                array_push($musics, $video);
                unset($videos[$key]);
            } elseif (($video->getCategorie() == 3 || $video->getCategorie() == 4) && count($etranges) < Video::INDEX_MAX_AUTRES) {
                array_push($etranges, $video);
                unset($videos[$key]);
            } else {
                continue;
            }
            $i ++;
            
            if ($i >= Video::INDEX_MAX_TOTAL) {
                break;
            }
        }
        
        return $this->render('SHUFLERShuflerBundle:Video:index.html.twig', array(
            'videos' => $videos,
            'anims' => $anims,
            'musics' => $musics,
            'etranges' => $etranges
        ));
    }

    /**
     * Search
     *
     * @param Request $request            
     * @param unknown $page            
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request, $page)
    {
        $search = $request->get('search_field');
        
        $videos = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Video')
            ->searchVideos($search, $page, Video::MAX_LIST);
        $videos_count = count($videos);
        $pagination = array(
            'search_field' => $search,
            'page' => $page,
            'route' => 'shufler_search',
            'pages_count' => ceil($videos_count / Video::MAX_LIST),
            'route_params' => array(
                'search_field' => $search
            )
        );
        $videos = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Video')
            ->searchVideos($search, $page, Video::MAX_LIST);
        return $this->render('SHUFLERShuflerBundle:Video:videos.html.twig', array(
            'search' => $search,
            'pagination' => $pagination,
            'videos_count' => $videos_count,
            'videos' => $videos
        ));
    }

    /**
     * Get Paginated List of Videos by cateory
     *
     * @param Request $request            
     * @param unknown $categorie            
     * @param unknown $page            
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getVideosAction(Request $request, $categorie, $genre, $periode, $page)
    {
        $videos = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Video')
            ->getPaginatedVideos($categorie, $genre, $periode, $page, Video::MAX_LIST);
        
        $videos_count = count($videos);
        
        $pagination = array(
            'page' => $page,
            'route' => 'shufler_videos',
            'pages_count' => ceil($videos_count / Video::MAX_LIST),
            'route_params' => $request->attributes->get('_route_params')
        );
        
        return $this->render('SHUFLERShuflerBundle:Video:videos.html.twig', array(
            'videos' => $videos,
            'categories' => [
                0 => 'ALL'
            ] + Video::CATEGORY_LIST,
            'genres' => [
                0 => 'ALL'
            ] + Video::GENRE_LIST,
            'periodes' => [
                0 => 'ALL'
            ] + Video::PERIOD_LIST,
            'pagination' => $pagination
        ));
    }

    /**
     * Get List of videos
     *
     * @param Request $request            
     * @param unknown $categorie            
     * @param unknown $page            
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $categorie, $page)
    {
        $videos_count = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Video')
            ->getCountByCategorie($categorie);
        $pagination = array(
            'page' => $page,
            'route' => 'article_list',
            'pages_count' => ceil($videos_count / Video::MAX_LIST),
            'route_params' => $request->attributes->get('_route_params')
        );
        
        $videos = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Video')
            ->getListByCategorie($categorie, $page, Video::MAX_LIST);
        
        return $this->render('SHUFLERShuflerBundle:Video:videos.html.twig', array(
            'videos' => $videos,
            'pagination' => $pagination
        ));
    }

    public function viewAction(Video $video)
    {
        return $this->render('SHUFLERShuflerBundle:Video:view.html.twig', array(
            'video' => $video
        ));
    }

    /**
     * Edit Video
     *
     * @param Request $request            
     * @param unknown $id            
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response @Security("has_role('ROLE_AUTEUR')")
     */
    public function editAction(Request $request, $id)
    {
        $video = new Video();
        
        if ($id != 0) {
            try {
                $video = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('SHUFLERShuflerBundle:Video')
                    ->getVideo($id);
            } catch (\Exception $e) {
                $this->get('session')
                    ->getFlashBag()
                    ->add('danger', $e->getMessage());
                return $this->redirect($this->generateUrl('shufler_homepage'));
            }
        }
        
        $form = $this->get('form.factory')->create(VideoType::class, $video);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();
            
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Vidéo bien enregistrée.');
            
            return $this->redirect($this->generateUrl('shufler_view', array(
                'id' => $video->getId()
            )));
        }
        
        $videokey = null;
        if ($request->get('videokey')) {
            $videokey = $request->get('videokey');
        }
        
        return $this->render('SHUFLERShuflerBundle:Video:edit.html.twig', array(
            'form' => $form->createView(),
            'video' => $video,
            'videokey' => $videokey
        ));
    }

    /**
     * Publish / unpublish a Video
     *
     * @param Video $video            
     * @param boolean $publish            
     * @return \Symfony\Component\HttpFoundation\RedirectResponse @Security("has_role('ROLE_AUTEUR')")
     */
    public function publishAction(Video $video, $publish = false)
    {
        $em = $this->getDoctrine()->getManager();
        $video->setPublished($publish);
        $em->persist($video);
        $em->flush();
        return $this->redirectToRoute('shufler_homepage');
    }

    /**
     * Get Videos unpublished + trash
     *
     * @return \Symfony\Component\HttpFoundation\Response @Security("has_role('ROLE_ADMIN')")
     */
    public function getTrashAction(Request $request, $page)
    {
        $videos = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Video')
            ->getPaginatedTrash($page, Video::MAX_LIST);
        
        $videos_count = count($videos);
        
        $pagination = array(
            'page' => $page,
            'route' => 'shufler_trash',
            'pages_count' => ceil($videos_count / Video::MAX_LIST),
            'route_params' => $request->attributes->get('_route_params')
        );
        
        return $this->render('SHUFLERShuflerBundle:Video:videos.html.twig', array(
            'videos' => $videos,
            'trash' => true,
            'pagination' => $pagination
        ));
    }

    /**
     *
     * Delete a Video
     *
     * @param Video $video            
     * @return \Symfony\Component\HttpFoundation\RedirectResponse @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Video $video)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($video);
        $em->flush();
        return $this->redirectToRoute('shufler_homepage');
    }

    /**
     * Couch Video Mode
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function couchAction($categorie, $genre, $periode)
    {
        $videos = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Video')
            ->getRandomVids($categorie, $genre, $periode);
        
        $playlist = array();
        $i = 0;
        
        $shufler = $this->get('shufler.filtre_affichage');
        
        foreach ($videos as $video) {
            $api = $shufler->getPlatform($video->getLien());
            
            if ($api === Video::YOUTUBE && $vid = $shufler->getIdentifer($video->getLien(), $api)) {
                array_push($playlist, $vid);
                $i ++;
            }
            
            if ($i >= Video::MAX_LIST_COUCH) {
                break;
            }
        }
        
        return $this->render('SHUFLERShuflerBundle:Video:couch.html.twig', array(
            'videos' => $playlist
        ));
    }
}
