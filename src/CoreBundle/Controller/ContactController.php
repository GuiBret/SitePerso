<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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

}
?>
