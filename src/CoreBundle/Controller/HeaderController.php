<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class HeaderController extends Controller // Génère le header de la page
{
    /**
     * @Route("/")
     */
    public function generateHeaderAction($projets, $path, $personal_projects) {
        
        return $this->render('CoreBundle:Default:header.html.twig', array(
            "projets"=> $projets,
            "path" => $path,
            "personal_projects" => $personal_projects
        ));
    }


}
