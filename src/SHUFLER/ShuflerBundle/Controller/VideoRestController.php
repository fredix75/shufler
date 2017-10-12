<?php
namespace SHUFLER\ShuflerBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation as Doc;
use SHUFLER\ShuflerBundle\Entity\Video;
use SHUFLER\ShuflerBundle\Form\VideoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VideoRestController extends FOSRestController
{
	
	/**
	 * GET All Videos
	 * 
     * @Rest\Get("/videos")
     *
     * @Doc\ApiDoc(
     *     section="Videos",
     *     resource=true,
     *     description="Get a List of videos",
     *     statusCodes={
     *          200="Returned when successful",
     *     }
     * )
	 *
	 * @View(serializerGroups={"List"})
	 *
	 */
	public function getVideosAction(){
		$videos = $this->getDoctrine()->getRepository('SHUFLERShuflerBundle:Video')->findBy(array('priorite'=>'1'));
		if(!$videos){
			throw $this->createNotFoundException();
		}

		return $videos;
	}
	
	/**
	 * GET One Video
	 *
	 * @Rest\Get("/video/{id}",
	 * 	    requirements={"id"="\d+"}
	 * )
	 *
	 * @Doc\ApiDoc(
	 *     section="Videos",
	 *     resource=true,
	 *     description="Get a video",
	 *     requirements={
	 *      {
	 *         "name"="id",
	 *         "dataType"="integer",
	 *         "requirement"="\d+",
	 *         "description"="The video identifier."
	 *      }
	 *    },
	 *     statusCodes={
	 *          200="Returned when successful",
	 *     }
	 * )
	 *
	 *
	 * @param type $id
	 *
	 * @View(serializerGroups={"Default","Details"})
	 *
	 */
	public function getVideoAction(Video $video){
		$video = $this->getDoctrine()->getRepository('SHUFLERShuflerBundle:Video')->findOneById($video);
		return $video;
	}
	
	/**
	 * POST Video
	 * 
	 * @Rest\Post("/video")
	 *
	 *
	 * @Rest\View(statusCode=201)
	 *
	 * @Doc\ApiDoc(
     *     section="Videos",
	 *      description="Creates a new video.",
	 *      input = "SHUFLER\ShuflerBundle\Form\VideoType",
	 *      statusCodes={
	 *          201="Returned if category has been successfully created",
	 *          400="Returned if errors",
	 *          500="Returned if server error"
	 *      }
	 * )
	 * 
	 * Collection post action
	 * @return View|array
	 */
	public function postVideoAction(Request $request)
	{
		$video = new Video();
	    $form = $this->createForm(VideoType::Class, $video);
	    $form->handleRequest($request);
	    
	    if ($form->isValid()) {
	        $em = $this->getDoctrine()->getManager();
	        $em->persist($video);
	        $em->flush();
	
	        return $this->redirectView(
	                $this->generateUrl(
	                    'api_get_video',
	                    array('id' => $video->getId())
	                    ),
	                201
	        );
	    }
	
	    return $this->view('',400);
	}

	/**
	 * PUT ONE VIDEO
	 * 
	 * @Rest\Put("/video/{id}",
	 * 		requirements={"id"="\d+"}
	 * )
	 *
	 *
	 * @Rest\View(statusCode=204)
	 *
	 * @Doc\ApiDoc(
	 *     section="Videos",
	 *      description="Update a video.",
	 *      input = "SHUFLER\ShuflerBundle\Form\VideoType",
	 *      requirements={
	 *      {
	 *         "name"="id",
	 *         "dataType"="integer",
	 *         "requirement"="\d+",
	 *         "description"="The video identifier."
	 *      }
	 *    },
	 *      statusCodes={
	 *          204="Returned if category has been successfully updated",
	 *          400="Returned if errors",
	 *          500="Returned if server error"
	 *      }
	 * )
	 *
	 * Collection put action
	 * @return View|array
	 */
	public function putVideoAction(Request $request, $id)
	{
		$video=new Video();
		if($id!=0){
			try{
				$video=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Video')->getVideo($id);
				//return array($video);
			}catch(\Exception $e){
				return $this->view('',400);
			}
		}
		$form = $this->createForm(VideoType::Class, $video, array('method' => 'PUT'));
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($video);
			$em->flush();
	
			return $this->redirectView(
					$this->generateUrl(
							'api_get_video',
							array('id' => $video->getId())
							),
					204
					);
		}
	
		return $this->view('',400);
	}
	
	
	/**
	 * DELETE One Video
	 * 
	 * @Rest\Delete("/video/{id}",
	 * 		requirements={"id"="\d+"}
	 * )
	 *
	 *
	 * @Rest\View(statusCode=204)
	 *
	 * @Doc\ApiDoc(
	 *     section="Videos",
	 *      description="Delete a video.",
	 *      requirements={
	 *      {
	 *         "name"="id",
	 *         "dataType"="integer",
	 *         "requirement"="\d+",
	 *         "description"="The video identifier."
	 *      }
	 *    },
	 *      statusCodes={
	 *          204="Returned if category has been successfully deleted",
	 *          400="Returned if errors",
	 *          500="Returned if server error"
	 *      }
	 * )
	 *
	 * Collection put action
	 * @return View|array
	 */
	public function deleteVideoAction($id){
		if($id!=0){
			try{
				$video=$this->getDoctrine()->getManager()->getRepository('SHUFLERShuflerBundle:Video')->getVideo($id);
				//return array($video);
			}catch(\Exception $e){
				return $this->view('',400);
			}
			$em = $this->getDoctrine()->getManager();
			$em->remove($video);
			$em->flush();
			return $this->redirectView(
					$this->generateUrl(
							'api_get_videos'
							),
					204
					);			
		}
	}

}