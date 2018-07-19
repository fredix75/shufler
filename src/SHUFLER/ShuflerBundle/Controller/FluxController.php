<?php
namespace SHUFLER\ShuflerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SHUFLER\ShuflerBundle\Entity\Flux;
use SHUFLER\ShuflerBundle\Form\FluxType;
use Symfony\Component\HttpFoundation\Response;
use SHUFLER\ShuflerBundle\Entity\ChannelFlux;
use SHUFLER\ShuflerBundle\Form\ChannelFluxType;
use Symfony\Component\Validator\Constraints\Length;

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
                if ($flux->getType() === 1 && $flux->getLogo() != null) {
                    $infos[$flux->getId()]['pic'] = $flux->getPic();
                } else if ($flux->getType() === 1){
                    $infos[$flux->getId()]['pic'] = null;
                }
                if ($flux->getType() === 2 && $flux->getChannel() != null) {
                    $infos[$flux->getId()]['channel_logo'] = $flux->getChannel()->getLogo();
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
        
        $radios = $this->getDoctrine()
            ->getManager()
            ->getRepository('SHUFLERShuflerBundle:Flux')
            ->getRadios();
        
        return $this->render('SHUFLERShuflerBundle:Flux:radios.html.twig', array(
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
        
        return $this->render('SHUFLERShuflerBundle:Flux:links.html.twig', array(
            'links' => $links,
            'categories' => $categories
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
                return $this->redirect($this->generateUrl('shufler_rss'));
            }
        }
        
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
        
        $form = $this->createForm(ChannelFluxType::Class, $channel, array(
            'action' => $this->generateUrl('shufler_flux_new_channel'),
            'method' => 'POST'
        ));
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
            
            $response = new Response(json_encode([
                'success' => true,
                'id' => $data->getId(),
                'name' => $data->getName()
            ]));
            
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        }
        
        return $this->render('SHUFLERShuflerBundle:Flux:channelEdit.html.twig', array(
            'form' => $form->createView()
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
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($flux);
            $em->flush();
            return $this->redirectToRoute('shufler_homepage');
        } catch (\Exception $e) {
            $this->get('session')
                ->getFlashBag()
                ->add('danger', $e->getMessage());
            return $this->redirectToRoute('shufler_rss');
        }
    }

    /**
     * Delete Logo
     *
     * @param unknown $id            
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *        
     *  @Security("has_role('ROLE_AUTEUR')")
     */
    public function deleteLogoAction($id)
    {
        return $this->redirectToRoute('shufler_homepage');
    }
}