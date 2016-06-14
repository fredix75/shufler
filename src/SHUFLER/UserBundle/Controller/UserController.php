<?php
namespace SHUFLER\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class UserController extends Controller {

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function updateAction(){			
		$userManager = $this->get('fos_user.user_manager');

		$user = $userManager->findUserBy(array('id' => 8));
	
		$user->setRoles(array('ROLE_USER'));
		
		$userManager->updateUser($user);

		return $this->render('SHUFLERShuflerBundle:Main:blank.html.twig');
	}

}