<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 12/10/2017
 * Time: 02:32
 */

namespace CoreBundle\services;

use Symfony\Component\Translation\Translator;


class TranslateService // Contient les fonctions inhérentes à la traduction
{
    public function __construct() {
        $translator = new Translator('fr_FR');
        $this->translator = $translator;

    }

    public function translateProjectTitles($projects) {



        foreach($projects as $k=>$v) {
            $projects[$k]["nom"] = $this->translator->trans($v["nom"]);
        }

        return $projects;
    }
}