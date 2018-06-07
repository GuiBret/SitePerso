<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 20/02/2018
 * Time: 13:29
 */

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class AboutMeController extends Controller
{
    public function pageAboutMeAction(Request $request) {
        $service = $this->container->get("myservice");
        $projets = $service->getTrainingProjectList($request->getLocale());

        $content = $this->render("CoreBundle:Default:aboutme.html.twig", array(
            "pagename"=> "À propos de moi",
            "projets" => $projets,
            "path" => join("/", array_slice(preg_split("[/]", $request->getRequestUri()), 2))));

        return $content;
    }
}