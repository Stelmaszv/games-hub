<?php

namespace App\Controller;

use App\Generic\Auth\AuthenticationAPi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
{
    use AuthenticationAPi;
}
