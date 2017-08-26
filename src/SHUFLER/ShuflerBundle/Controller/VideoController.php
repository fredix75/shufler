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
            if (($video->getCategorie() == 1 || $video->getCategorie() == 9) && count($anims) < 4) {
                array_push($anims, $video);
                unset($videos[$key]);
                $i ++;
            } elseif ($video->getCategorie() == 2 && count($musics) < 8) {
                array_push($musics, $video);
                unset($videos[$key]);
                $i ++;
            } elseif (($video->getCategorie() == 3 || $video->getCategorie() == 4) && count($etranges) < 4) {
                array_push($etranges, $video);
                unset($videos[$key]);
                $i ++;
            }
            
            if ($i >= 16) {
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
    public function viewByCategorieAction(Request $request, $categorie, $page)
    {
       
        $videos_count = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Video')
            ->getCountByCategorie($categorie);
        
        $pagination = array(
            'page' => $page,
            'route' => 'shufler_viewByCategorie',
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

    /**
     * Get Paginated List of Videos by period
     * 
     * @param Request $request
     * @param unknown $periode
     * @param unknown $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewByPeriodeAction(Request $request, $periode, $page)
    {

        $videos_count = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Video')
            ->getCountByPeriode($periode);
        $pagination = array(
            'page' => $page,
            'route' => 'shufler_viewByPeriode',
            'pages_count' => ceil($videos_count / Video::MAX_LIST),
            'route_params' => $request->attributes->get('_route_params')
        );
        
        $videos = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Video')
            ->getListByPeriode($periode, $page, Video::MAX_LIST);
        
        return $this->render('SHUFLERShuflerBundle:Video:videos.html.twig', array(
            'videos' => $videos,
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * 
     * @Security("has_role('ROLE_AUTEUR')")
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
        
        $form = $this->get('form.factory')->create(new VideoType(), $video);
        
        // On vérifie que les valeurs entrées sont correctes
        if ($form->handleRequest($request)->isValid()) {
            
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
        
        if ($request->get('videokey')) {
            
            $videokey = $request->get('videokey');
        } else {
            $videokey = null;
        }
        
        // Ã€ ce stade, le formulaire n'est pas valide car :
        // - Soit la requÃªte est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requÃªte est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        return $this->render('SHUFLERShuflerBundle:Video:edit.html.twig', array(
            'form' => $form->createView(),
            'video' => $video,
            'videokey' => $videokey
        ));
    }

    /**
     * unpublish a video
     * 
     * @param Video $video
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function depublishedAction(Video $video)
    {
        $em = $this->getDoctrine()->getManager();
        $video->setPublished(false);
        $em->persist($video);
        $em->flush();
        return $this->redirectToRoute('shufler_homepage');
    }

    /**
     * Publish a Video
     * 
     * @param Video $video
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Security("has_role('ROLE_AUTEUR')")     
     */
    public function publishedAction(Video $video)
    {
        $em = $this->getDoctrine()->getManager();
        $video->setPublished(true);
        $em->persist($video);
        $em->flush();
        return $this->redirectToRoute('shufler_homepage');
    }

    /**
     * 
     * Delete a Video
     * 
     * @param Video $video
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Security("has_role('ROLE_AUTEUR')")
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
    public function couchAction()
    {
        $videos = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Video')
            ->getRandomVids();
        
        $yt1 = "http://www.youtube.com/embed/";
        $yt2 = "//www.youtube.com/embed/";
        
        $playlist = array();
        $i = 0;
        
        foreach ($videos as $video) {
            if (mb_substr($video->getLien(), 0, strlen($yt1)) == $yt1 && $i < 100) {
                $vid = substr($video->getLien(), strlen($yt1));
                array_push($playlist, $vid);
                $i ++;
            } elseif (mb_substr($video->getLien(), 0, strlen($yt2)) == $yt2 && $i < 100) {
                $vid = substr($video->getLien(), strlen($yt2));
                array_push($playlist, $vid);
                $i ++;
            }
            
            if ($i >= 99) {
                break;
            }
        }
        
        return $this->render('SHUFLERShuflerBundle:Video:couch.html.twig', array(
            'videos' => $playlist
        ));
    }
}
