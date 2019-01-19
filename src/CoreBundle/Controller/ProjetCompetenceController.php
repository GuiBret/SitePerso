<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 31/01/2018
 * Time: 18:34
 */

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class ProjetCompetenceController extends Controller
{
    /**
     * @Route("/projets/{id}")
     */


    public function pageCompetencesAction(Request $request)
    {
        /* Header mgmt */
        $service = $this->container->get("myservice");
        $locale = $request->getLocale();
        $liste_projets = $service->getProjectsWithShortDescriptions($locale);
        $personal_projects = $service->getPersonalProjects($locale);
        $path = join("/", array_slice(preg_split("[/]", $request->getRequestUri()), 2));



        $competences = $service->getAllSkills();

        $content = $this->render('CoreBundle:Default:template-competencesparprojet.html.twig', array(
            "projets" => $liste_projets,
            "competences" => $competences,
            "path" => $path,
            "locale" => $locale,
            "pagename" => "Classement par technologies",
            "personal_projects" => $personal_projects
        ));
        return $content;
    }

    public function ajaxCompetencesAction(Request $request) {

        $service = $this->container->get("myservice");

        $locale = $request->getLocale();

        $results = $service->getAjaxSkillsByProject($locale);


        return new JsonResponse(json_encode($results, JSON_UNESCAPED_UNICODE));
    }
}
