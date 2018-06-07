<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProjetController extends Controller
{
    /**
     * @Route("/projets/{id}")
     */

    public function viewAction($id, Request $request)
    {
        $service = $this->container->get("myservice");
        $locale = $request->getLocale();
        $projet = $service->getProjectInfo($id, $locale);

        if(empty($projet)) {
            throw $this->createNotFoundException("Cette page n'existe pas.");
        }

        $path = join("/", array_slice(preg_split("[/]", $request->getRequestUri()), 2));
        $variations = $service->getVariations($id);


        $liste_projets = $service->getTrainingProjectList($locale);
        $personal_projects = $service->getPersonalProjects($locale);
        $competences = $service->getSkills($id, $locale);
        $ressources = $service->getResources($id);

        $images = $service->getImagesFromProjectId($id);

        if($request->getLocale() != "fr") {
            $projet = $this->translateProjectInfo($projet);
        }

        $content = $this->render('CoreBundle:Default:projet.html.twig', array(
            "projet" => $projet,
            "projets" => $liste_projets,
            "competences" => $competences,
            "ressources" => $ressources,
            "path" => $path,
            "variations" => $variations,
            "locale" => $locale,
            "images" => $images,
            "personal_projects" => $personal_projects
        ));
        return $content;
    }


    public function translateProjectInfo($projet) {
        $translator = $this->get("translator");
        $projet["nom"] = $translator->trans($projet["nom"]);

        return $projet;
    }

}
?>
