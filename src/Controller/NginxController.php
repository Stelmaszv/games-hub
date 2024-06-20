<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NginxController extends AbstractController
{
    private function getView() : Response
    {
        $directoryName = './build'; 
        $script = [];
        $style = [];
        
        if (is_dir($directoryName)) {
            if ($dirHandle = opendir($directoryName)) {
                while (($file = readdir($dirHandle)) !== false) {
                    
                    $pathInfo = pathinfo($file);
                    if(isset($pathInfo['extension'])){
                        if($pathInfo['extension'] == 'js'){
                            $script[] = $file;
                        }

                        if($pathInfo['extension'] == 'css'){
                            $style[] = $file;
                        }
                    }
                    
                }
                closedir($dirHandle);
            }
        }

        return $this->render('home.html.twig',[
            'script' => $script,
            'style' => $style
        ]);
    }

	/**
     * @Route("", name="home")
     */
    public function home(): Response
    {
        return $this->getView();
    }

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