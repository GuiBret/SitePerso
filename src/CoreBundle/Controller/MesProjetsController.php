<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class MesProjetsController extends Controller
{
    /**
     * @Route("/mes-projets/{nom-projet}"), ouvre un projet
     */
    public function getProjetAction($nom_projet, Request $request)
    {
        $service = $this->container->get("myservice");
        $locale = $request->getLocale();
        $path = join("/", array_slice(preg_split("[/]", $request->getRequestUri()), 2));
        $personal_projects= $service->getPersonalProjects($request->getLocale());

        switch($nom_projet) {
            case "pcec":
                $content = $this->redirect("/p/SiteChalet/");
                // throw $this->createNotFoundException("Ce projet n'est pas encore accessible.");
            break;
            case "pjdp":
                $content = $this->render("CoreBundle:Default:template-jeuplateau.html.twig", array("pagename" => "Jeu de plateau", "personal_projects" => $personal_projects, "projets" => $service->getTrainingProjectList($locale), "path"=>$path));
            break;
            case "pfdf": // Festival de films
                $content = $this->redirect("/p/FestivalFilms/");
            break;
            case "soko": // Sokonyan
                //$content = $this->render("CoreBundle:Default:template-sokonyan.html.twig", array("pagename" => "Jeu de plateau", "projets" => $service->getProjectList($locale), "path"=>$path));
                $content = $this->redirect("/p/Sokoban/index.html");
            break;
            case "avisrest": // Avis de restaurants
                //$content = $this->redirect("/p/AvisRestaurants/index.php");
                $content = $this->render("CoreBundle:Default:template-avisrestaurants.html.twig", array("pagename" => "Avis de restaurants", "personal_projects" => $personal_projects, "projets" => $service->getTrainingProjectList($locale), "path" => $path));
            break;
            case "gencit": // Générateur de citations
                $content = $this->render("CoreBundle:Default:template-gencit.html.twig", array("pagename" => "Générateur de citations", "personal_projects" => $personal_projects, "projets" => $service->getTrainingProjectList($locale), "path" => $path));
            break;

            default:
                throw $this->createNotFoundException("Cette page n'existe pas.");
            break;


        }

        return $content;

    }
}
?>
