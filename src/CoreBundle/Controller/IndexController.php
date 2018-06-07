<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;




class IndexController extends Controller
{
    /**
     * @Route("/", name="secure", schemes={"https"})
     */
    private $service;


    public function indexAction(Request $request) {


        $pagename = "Guillaume Bretzner, développeur Web";
        $this->service = $this->container->get("myservice");

        $projets_formation = $this->service->getTrainingProjectList($request->getLocale());
        $personal_projects= $this->service->getPersonalProjects($request->getLocale());



        if($request->getLocale() != "fr") { // Anglais, à voir par la suite
            $projets_formation = $this->translateProjectTitles($projets_formation);
            $pagename = "Guillaume Bretzner, Web Developer";
        }

        $content = $this->render('CoreBundle:Default:index.html.twig', array(
            "projets" => $projets_formation,
            "pagename" => $pagename,
            "path" => join("/", array_slice(preg_split("[/]", $request->getRequestUri()), 2)),
            "personal_projects" => $personal_projects
        ));

        return $content;
    }

    public function translateProjectTitles($projects) {

        $translator = $this->get("translator");

        foreach($projects as $k=>$v) {
            $projects[$k]["nom"] = $translator->trans($v["nom"]);
        }

        return $projects;
    }
}
?>
