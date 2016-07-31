<?php

namespace COMMON\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SHUFLERShuflerBundle:Default:index.html.twig');
    }
}
