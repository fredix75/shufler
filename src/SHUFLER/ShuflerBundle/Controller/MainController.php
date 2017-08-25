<?php
namespace SHUFLER\ShuflerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use SHUFLER\ShuflerBundle\Entity\Video;

class MainController extends Controller
{
    /**
     * Call Navigation Menu 
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navigationAction()
    {   
        return $this->render('SHUFLERShuflerBundle:Main:nav.html.twig', array(
            'categories' => Video::CATEGORY_LIST,
            'periodes' => Video::PERIOD_LIST
        ));
    }

    /**
     * Suggestions For Search Engine 
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchAjaxAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $search = $request->query->get('query');
            $videos = $this->getDoctrine()
                ->getManager()
                ->getRepository('SHUFLERShuflerBundle:Video')
                ->searchAjax($search);
            $suggestions = array();
            if ($videos) {
                foreach ($videos as $video) {
                    $suggestions['suggestions'][] = $video;
                }
            } else {
                $suggestions['suggestions'] = array();
            }
            
            return new JsonResponse(json_encode($suggestions));
        }
    }

}