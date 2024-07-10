<?php

namespace App\Controller\Nginx;

use App\Controller\Nginx\section\Auth;
use App\Controller\Nginx\section\Publisher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NginxController extends AbstractController
{
    use Publisher;
    use Auth;

    private function getView(): Response
    {
        $directoryName = './build';
        $script = [];
        $style = [];

        if (is_dir($directoryName)) {
            if ($dirHandle = opendir($directoryName)) {
                while (($file = readdir($dirHandle)) !== false) {
                    $pathInfo = pathinfo($file);
                    if (isset($pathInfo['extension'])) {
                        if ('js' == $pathInfo['extension']) {
                            $script[] = $file;
                        }

                        if ('css' == $pathInfo['extension']) {
                            $style[] = $file;
                        }
                    }
                }
                closedir($dirHandle);
            }
        }

        return $this->render('home.html.twig', [
            'script' => $script,
            'style' => $style,
        ]);
    }

    /**
     * @Route("", name="home")
     */
    public function home(): Response
    {
        return $this->getView();
    }
}
