<?php

namespace App\Controller\Nginx\section;

use Symfony\Component\HttpFoundation\Response;

trait Auth{

    /**
     * @Route("/register", name="register-nginx")
     */
    public function register(): Response
    {
        return $this->getView();
    }

    /**
     * @Route("/login", name="login-nginx")
     */
    public function login(): Response
    {
        return $this->getView();
    }
}