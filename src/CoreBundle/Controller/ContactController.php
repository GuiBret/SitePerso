<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContactController extends Controller
{
    /**
     * @Route("/")
     */
    public function generateFormAction(Request $request)
    {
        $service = $this->container->get("myservice");

        $projets = $service->getTrainingProjectList($request->getLocale());
        $personal_projects = $service->getPersonalProjects($request->getLocale());

        $content = $this->render('CoreBundle:Default:contact.html.twig', array(
            "projets" => $projets,
            "personal_projects" => $personal_projects,
            "pagename" => $this->get("translator")->trans("Me contacter"),
            "path" => join("/", array_slice(preg_split("[/]", $request->getRequestUri()), 2))
        ));
        return $content;
    }

    public function ajaxEmailAction(Request $request) {

        // Vérification des champs requis
        if(!(isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['message']))) {
            return new JsonResponse(array("result" => "KO"));
        } else {
            $message = $_POST["email"] +  " a envoyé le message suivant : \r\n" + $_POST["message"];

            // Si l'envoi du mail a merdé
            if(mail("guillaume.bretzner@gmail.com", "Message de " + $_POST['nom'], $message)) {
                return new JsonResponse(array("result" => "OK"));

            } else {
                return new JsonResponse(array("result" => "KO"));
            }
        }
    }

}
?>
