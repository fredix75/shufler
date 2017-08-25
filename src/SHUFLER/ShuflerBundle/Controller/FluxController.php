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
            
            // $contenu=$fluxParser->convertXML($rss->getUrl());
            $contenu = $rss->getContenu();
            
            for ($i = $debut; $i < $debut + 6; $i ++) {
                if (isset($contenu[$i])) {
                    $contenu[$i]->title = stripslashes($contenu[$i]->title);
                    $contenu[$i]->description = stripslashes($contenu[$i]->description);
                    
                    $infos[] = $contenu[$i];
                }
            }
            
            return new Response(json_encode($infos));
        }
        
        $rss = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Flux')
            ->getRss();
        
        foreach ($rss as $flux) {
            $libe = [];
            $jsonKeys = [];
            if ($flux->getName() == 'Liberation') {
                $libe[$flux->getId()]['flux'] = $flux->getContenu();
                $libe[$flux->getId()]['name'] = $flux->getName();
                $libe[$flux->getId()]['pic'] = $flux->getPic();
            } else {
                $infos[$flux->getId()]['id'] = $flux->getId();
                $jsonKeys[] = $flux->getId();
                $contenu = $flux->getContenu();
                $infos[$flux->getId()]['name'] = $flux->getName();
                if ($flux->getLogo() != null) {
                    $infos[$flux->getId()]['pic'] = $flux->getPic();
                } else {
                    $infos[$flux->getId()]['pic'] = null;
                };
                $infos[$flux->getId()]['pages'] = ceil(count($contenu) / 6);
            }
        }
        
        shuffle($infos);
        
        return $this->render('SHUFLERShuflerBundle:Flux:rss.html.twig', array(
            'infos' => $infos,
            'libe' => $libe,
            'jsonKeys' => json_encode($jsonKeys)
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
            
            $contenu = $rss->getContenu();
            for ($i = $debut; $i < $debut + 6; $i ++) {
                $infos[] = $contenu[$i];
            }
            
            return new Response(json_encode($infos));
        }
        
        $rss = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Flux')
            ->getPodcast();
        
        $jsonKeys = array();
        
        foreach ($rss as $key) {
            $infos[$key->getId()]['id'] = $key->getId();
            $jsonKeys[] = $key->getId();
            $infos[$key->getId()]['contenu'] = $key->getContenu();
            $infos[$key->getId()]['name'] = $key->getName();
            if ($key->getLogo() != null) {
                $infos[$key->getId()]['pic'] = $key->getPic();
            } else {
                $infos[$key->getId()]['pic'] = null;
            }
            $infos[$key->getId()]['pages'] = ceil(count($infos[$key->getId()]['contenu']) / 6);
        }
        
        return $this->render('SHUFLERShuflerBundle:Flux:podcast.html.twig', array(
            'infos' => $infos,
            'jsonKeys' => json_encode($jsonKeys)
        ));
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
        
        $radios = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Flux')
            ->getRadios();
        
        return $this->render('SHUFLERShuflerBundle:Flux:radios.html.twig', array(
            'radios' => $radios
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
            
            if ($key->getLogo() != null) {
                $infos[$key->getName()]['pic'] = $key->getPic();
            } else {
                $infos[$key->getName()]['pic'] = null;
            }
        }
        
        return $this->render('SHUFLERShuflerBundle:Flux:dailyPod.html.twig', array(
            'infos' => $infos
        ));
    }

    /**
     *
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
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function fluxEditAction(Request $request, $id)
    {
        $flux = new Flux();
        
        if ($id != 0) {
            try {
                $flux = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('SHUFLERShuflerBundle:Flux')
                    ->getFlux($id);
            } catch (\Exception $e) {
                $this->get('session')
                    ->getFlashBag()
                    ->add('danger', $e->getMessage());
                return $this->redirect($this->generateUrl('shufler_shufler_rss'));
            }
        }
        
        $form = $this->createForm(new FluxType(), $flux);
        
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($flux);
            $em->flush();
            
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Flux bien enregistré.');
            
            return $this->redirect($this->generateUrl('shufler_shufler_flux_edit', array(
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
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function deleteAction(Flux $flux)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($flux);
            $em->flush();
            return $this->redirectToRoute('shufler_shufler_homepage');
        } catch (\Exception $e) {
            $this->get('session')
                ->getFlashBag()
                ->add('danger', $e->getMessage());
            return $this->redirectToRoute('shufler_shufler_rss');
        }
    }

    /**
     * Delete Logo
     * 
     * @param unknown $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function deleteLogoAction($id)
    {
        return $this->redirectToRoute('shufler_shufler_homepage');
    }
}