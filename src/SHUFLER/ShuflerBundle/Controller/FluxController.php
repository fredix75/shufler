<?php
namespace SHUFLER\ShuflerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SHUFLER\ShuflerBundle\Entity\Flux;
use SHUFLER\ShuflerBundle\Form\FluxType;
use Symfony\Component\HttpFoundation\Response;


class FluxController extends Controller
{

    /**
     * Get All Flows RSS.
     *
     * @param Request $request
     *            request of the Flow
     *            
     * @return Response Ajax or Rendering template
     */
    public function rssAction(Request $request)
    {
        $infos = array();
        
        if ($request->isXmlHttpRequest()) {
            $id = $request->query->get('pod');
            
            $rss = $this->getDoctrine()
                ->getManager()
                ->getRepository('SHUFLERShuflerBundle:Flux')
                ->getFlux($id);
            $page = $request->query->get('page');
            $debut = ($page - 1) * 6;
            
            // $contenu=$fluxParser->convertXML($rss->getUrl())
            $contenu = $rss->loadContent();
            
            for ($i = $debut; $i < $debut + 6; $i ++) {
                if (isset($contenu[$i])) {
                    $contenu[$i]->title = stripslashes($contenu[$i]->title);
                    $contenu[$i]->description = stripslashes($contenu[$i]->description);
                    
                    $infos[] = $contenu[$i];
                }
            }
            //$infos['pages'] = ceil(count($contenu) / 6);

            return new Response(json_encode($infos));
        }
        
        $rss = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Flux')
            ->getRss();
        
       $flux = $this->formatFlux($rss);
        
        shuffle($flux['infos']);
        
        return $this->render('SHUFLERShuflerBundle:Flux:rss.html.twig', array(
            'infos' => $flux['infos'],
            'jsonKeys' => json_encode($flux['jsonKeys'])
        ));
    }
    
    /**
     * Get All Flows Podcast.
     *
     * @param Request $request
     *            request of the Flow
     *
     * @return Response Ajax or Rendering template
     */
    public function podcastAction(Request $request)
    {
        $infos = array();
        
        if ($request->isXmlHttpRequest()) {
            $pod = $request->query->get('pod');
            
            $rss = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Flux')
            ->getFlux($pod);
            $page = $request->query->get('page');
            $debut = ($page - 1) * 6;
            
            $contenu = $rss->loadContent();
           
            $infos['pages'] = ceil(count($contenu) / 6);
            
            for ($i = $debut; $i < $debut + 6; $i ++) {
                $infos[] = $contenu[$i];
            }
            
            return new Response(json_encode($infos));
        }
        
        $rss = $this->getDoctrine()
        ->getManager()
        ->getRepository('SHUFLERShuflerBundle:Flux')
        ->getPodcast();
        
        
        $flux = $this->formatFlux($rss);

        $infos['pages'] = 12;
        return $this->render('SHUFLERShuflerBundle:Flux:podcast.html.twig', array(
            'infos' => $flux['infos'],
            'jsonKeys' => json_encode($flux['jsonKeys'])
        ));
    }

    /**
     * format Flux to display
     *
     * @param unknown $rss            
     * @return array[]|number[]|NULL[][]
     */
    private function formatFlux($rss)
    {
        $infos = [];
        $jsonKeys = [];
        foreach ($rss as $flux) {
                $infos[$flux->getId()]['id'] = $flux->getId();
                $jsonKeys[] = $flux->getId();
                $infos[$flux->getId()]['name'] = $flux->getName();
                if ($flux->getType() === 1 && $flux->getOldImage() != null) {
                    $infos[$flux->getId()]['pic'] = $flux->getOldImage();
                } else if ($flux->getType() === 1){
                    $infos[$flux->getId()]['pic'] = null;
                }
                if ($flux->getType() === 2 && $flux->getChannel() != null) {
                    $infos[$flux->getId()]['channel_logo'] = $flux->getChannel()->getOldImage() ? $flux->getChannel()->getOldImage() : null;
                    $infos[$flux->getId()]['channel_name'] = $flux->getChannel()->getName();
                } else if ($flux->getType() === 2){
                    $infos[$flux->getId()]['channel_logo'] = null;
                    $infos[$flux->getId()]['channel_name'] = null;
                }
                $infos[$flux->getId()]['pages'] = 12;
            
        }
        
        $result = [];
        $result['infos'] = $infos;
        $result['jsonKeys'] = $jsonKeys;
        
        return $result;
    }

    /**
     * Get All Flows radios.
     *
     * @param Request $request
     *            request of the Flow
     *            
     * @return Response Ajax or Rendering template
     */
    public function radioAction(Request $request)
    {
        error_reporting(0);
        
        $genre_radios = Flux::RADIO_TYPE;
        
        $radios = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Flux')
            ->getRadios();
        
        return $this->render('SHUFLERShuflerBundle:Flux:radios.html.twig', array(
            'genres' => $genre_radios,
            'radios' => $radios
        ));
    }
    
    /**
     * Get List of Links
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function linksAction()
    {
        $links = $this->getDoctrine()
        ->getManager()
        ->getRepository('SHUFLERShuflerBundle:Flux')
        ->getLinks();
        
        $categories = Flux::LINK_TYPE;
        $categories['']= 'Autres';
        
        return $this->render('SHUFLERShuflerBundle:Flux:links.html.twig', array(
            'links' => $links,
            'categories' => $categories
        ));
    }
    
    /**
     * Get All Flows playlists.
     *
     * @param Request $request
     *            request of the Flow
     *
     * @return Response Ajax or Rendering template
     */
    public function playlistAction(Request $request)
    {
        error_reporting(0);
                
        $playlists = $this->getDoctrine()
        ->getManager()
        ->getRepository('SHUFLERShuflerBundle:Flux')
        ->getPlaylists();
        
        return $this->render('SHUFLERShuflerBundle:Flux:playlists.html.twig', array(
            'playlists' => $playlists
        ));
    }
    
    

    /**
     * Get Tweets API Tweeter
     *
     * @param Request $request
     *            request of the Flow
     *            
     * @return Response Ajax or Rendering template
     */
    public function tweeterAction(Request $request)
    {
        $tweets = array();
        $search = null;
        $twitter = $this->get('endroid.twitter');
        
        if ($request->get('search_tweeter')) {
            $search = $request->get('search_tweeter');
            
            $parameters = array(
                'q' => $search,
                'lang' => 'fr',
                'count' => 100
            );
            $response = $twitter->query('/search/tweets', 'GET', 'json', $parameters);
            // var_dump($response->getContent());exit;
            foreach (json_decode($response->getContent())->statuses as $i => $tweet) {
                $tweets[$i]['id'] = $tweet->id_str;
                $tweets[$i]['screen'] = 'x';
            }
        } else {
            $response = $twitter->getTimeline([
                'count' => 5000
            ]);
            
            foreach ($response as $i => $tweet) {
                $tweets[$i]['id'] = $tweet->id_str;
                $tweets[$i]['screen'] = 'x';
            }
        }
        return $this->render('SHUFLERShuflerBundle:Flux:tweeter.html.twig', array(
            'tweets' => $tweets,
            'search' => $search
        ));
    }

    /**
     * UNUSED Insee test
     *
     * @param Request $request            
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function inseeAction(Request $request)
    {
        $flux = new Flux();
        $flux->setUrl('http://www.bdm.insee.fr/series/sdmx/data/SERIES_BDM/001565183');
        $test = simplexml_load_file('http://www.bdm.insee.fr/series/sdmx/data/SERIES_BDM/001565183')->{'Dataset'};
        
        return $this->render('SHUFLERShuflerBundle:Flux:flux.html.twig', array(
            'flux' => $test
        ));
    }

    /**
     * Get All most recently podcasts.
     *
     * @param Request $request
     *            request of the Flow
     *            
     * @return Rendering template
     */
    public function dailyPodAction(Request $request)
    {
        error_reporting(0);
        $infos = array();
        
        $rss = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Flux')
            ->getPodcast();
        
        foreach ($rss as $key) {
            $infos[$key->getName()]['id'] = $key->getId();
            $contenu = $rss->getContenu();
            $infos[$key->getName()]['contenu'] = $contenu[0];
            
            if ($key->getImage() != null) {
                $infos[$key->getName()]['pic'] = $key->getOldImage();
            } else {
                $infos[$key->getName()]['pic'] = null;
            }
        }
        
        return $this->render('SHUFLERShuflerBundle:Flux:dailyPod.html.twig', array(
            'infos' => $infos
        ));
    }

    /**
     * Edit Flow.
     *
     * @param Request $request
     *            request of the Form
     * @param
     *            Id of the Flow
     *            
     * @return Rendering template
     *        
     *        
     *  @Security("has_role('ROLE_AUTEUR')")
     */
    public function fluxEditAction(Request $request, Flux $flux = null)
    {       
        if(!$flux) $flux = new Flux();
        
        $form = $this->createForm(FluxType::Class, $flux);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($flux);
            $em->flush();
            
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Flux bien enregistré.');
            
            return $this->redirect($this->generateUrl('shufler_flux_edit', array(
                'id' => $flux->getId()
            )));
        }
        
        return $this->render('SHUFLERShuflerBundle:Flux:fluxEdit.html.twig', array(
            'form' => $form->createView(),
            'flux' => $flux
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
    public function deleteAction(Flux $flux)
    {
        $type_flux = $flux->getType(); 
        $em = $this->getDoctrine()->getManager();
        try {
            $flux->deleteLogo($this->getParameter('logo_directory') . '/' . $flux->getOldImage());
            $em->remove($flux);
            $em->flush();
        } catch (\Exception $e) {
            $this->get('session')
                ->getFlashBag()
                ->add('danger', $e->getMessage());
        }
        return $this->getBack($type_flux);
    }

    /**
     * Delete Image
     *
     * @param Flux $flux           
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *        
     *  @Security("has_role('ROLE_AUTEUR')")
     */
    public function deleteLogoAction(Flux $flux)
    {
        try{            
            $flux->deleteLogo($this->getParameter('logo_directory') . '/' . $flux->getOldImage());
            $em = $this->getDoctrine()->getManager();
            $flux->setOldImage();
            $flux->setImage();
            $em->flush();
            
        } catch (\Exception $e) {
            $this->get('session')
            ->getFlashBag()
            ->add('danger', $e->getMessage());
        }
        
        return $this->redirect($this->generateUrl('shufler_flux_edit', array(
            'id' => $flux->getId()
        )));
    }
    
    private function getBack($type_flux) {
        switch($type_flux) {
            case 1:
                return $this->redirectToRoute('shufler_rss');
            case 2:
                return $this->redirectToRoute('shufler_podcast');
            case 3:
                return $this->redirectToRoute('shufler_radio');
            case 4:
                return $this->redirectToRoute('shufler_links');
        }
    }

}