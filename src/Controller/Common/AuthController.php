<?php

namespace App\Controller\Common;

use App\Generic\Auth\AuthenticationAPi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
{
    use AuthenticationAPi;
}
