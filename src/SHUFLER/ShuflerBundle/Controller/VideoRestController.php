<?php
namespace SHUFLER\ShuflerBundle\Controller;

use SHUFLER\ShuflerBundle\Entity\Video; 
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\HttpFoundation\Request;
use SHUFLER\ShuflerBundle\Form\VideoType;
use Symfony\Component\HttpFoundation\Response;

class VideoRestController extends Controller
{
	/**
	* 
    * @Rest\Get("/video/{id}",
    * 		name="app_api_video",
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
	 * 
     * @Rest\Get("/videos",
     * 		name="app_api_video")
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
	 * @Rest\Post("/video", name="app_new_video")
	 *
	 *
	 * @Rest\View(statusCode=201)
	 *
	 * @Doc\ApiDoc(
     *     section="Videos",
	 *      description="Creates a new video.",
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
	public function postVideoAction()
	{

		$video = new Video();

	    $form = $this->createForm(new VideoType(), $video);
	    $form->handleRequest($this->getRequest());
	    $video->setTitre('fdfd');
	    if ($form->isValid()) {
	        $em = $this->getDoctrine()->getManager();
	        $em->persist($video);
	        $em->flush();
	
	        return $this->redirectView(
	                $this->generateUrl(
	                    'app_api_video',
	                    array('id' => $video->getId())
	                    ),
	                201
	        );
	    }
	
	    return array(
	        'form' => $video,
	    );
	}


	private function processForm(Video $video)
	{
	/*
		$form = $this->createForm(new VideoType(), $video);
		$form->handleRequest($this->getRequest());
	
		if ($form->isValid()) {
			   $em = $this->getDoctrine()->getManager();
   			$em->persist($video);
    		$em->flush();
			$statusCode=201;
	
			$response = new Response();
			$response->setStatusCode($statusCode);
	
			// set the `Location` header only when creating new resources
			if (201 === $statusCode) {
				$response->headers->set('Location',
						$this->generateUrl(
								'shufler_api_video', array('id' => $video->getId()),
								true // absolute
								)
						);
			}
			return $response;
		}
		return 400;
	  */
		print_r($this->getRequest());
	}
}