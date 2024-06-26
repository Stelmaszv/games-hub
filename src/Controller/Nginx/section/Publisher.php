<?php

namespace App\Controller\Nginx\section;

use Symfony\Component\HttpFoundation\Response;

trait Publisher
{
    /**
     * @Route("/publishers/list", name="publishers-list")
     */
    public function publishersList(): Response
    {
        return $this->getView();
    }

    /**
     * @Route("/publishers/add", name="publishers-add")
     */
    public function publishersAdd(): Response
    {
        return $this->getView();
    }

    /**
     * @Route("/publisher/show/{id}", name="publishers-show")
     */
    public function publishersShow(): Response
    {
        return $this->getView();
    }
}
