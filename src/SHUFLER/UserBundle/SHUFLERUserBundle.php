<?php
namespace SHUFLER\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SHUFLERUserBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
