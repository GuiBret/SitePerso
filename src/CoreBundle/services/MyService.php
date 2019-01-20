<?php

namespace CoreBundle\services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use CoreBundle\services\TranslateService;
use Doctrine\DBAL\FetchMode;




class MyService {
    public function __construct(EntityManager $em, RequestStack $rs, TranslateService $translateService) {
        $this->ts = $translateService;
        $this->locale = $rs->getCurrentRequest()->getLocale();

        $this->em = $em;

    }
    public function getTrainingProjectList($locale) {
        try {

            $conn = $this->em->getConnection();

            $stmt = $conn->executeQuery("SELECT nom, nom_short from infos_site where type_projet=0 order by ordre_projet desc"); // type_projet : 0 => Openclassrooms projects, 1 : personal projects
            $stmt->execute();
            $data = $stmt->fetchAll();

            if($locale != "fr") {
                $data = $this->ts->translateProjectTitles($data);
            }
            //echo serialize($data);
            return $data;

        }
        catch(PDOException $e) {
            print "Connection ratée". $e->getMessage();
            die();
        }

    }

    public function getProjectInfo($projectId, $locale) {
        try {

            $conn = $this->em->getConnection();

            $stmt = $conn->executeQuery("SELECT *, (select texte from description where nom_short='$projectId' and langue='$locale') as 'description' from infos_site where nom_short='$projectId' order by ordre_projet desc");
            $stmt->execute();

            return $stmt->fetch();

        }
        catch(PDOException $e) {
            print "Connection ratée". $e->getMessage();
            die();
        }

    }

    public function getResources($id) {
        try {

            $conn = $this->em->getConnection();

            $stmt = $conn->executeQuery("select nom_ressource, url_ressource from ressources where projet_concerne=(select id from infos_site where nom_short=\"$id\")");
            $stmt->execute();

            return $stmt->fetchAll();

        }
        catch(PDOException $e) {
            print "Connection ratée". $e->getMessage();
            die();
        }

    }

    public function getSkills($projectId) {

        try {
            $em = $this->em;
            $conn = $em->getConnection();

            $stmt = $conn->executeQuery("select fonct.nom_fct from fcts_projets fcts left join fonctionnalites fonct on fcts.id_fonctionnalite = fonct.id where fcts.projet_concerne=(select id from infos_site where nom_short=\"$projectId\")");
            $stmt->execute();

            return $stmt->fetchAll();

        }
        catch(PDOException $e) {
            print "Connection ratée". $e->getMessage();
            die();
        }

    }

    public function getVariations($projectId) {

        try {
            $em = $this->em;
            $conn = $em->getConnection();

            $stmt = $conn->executeQuery("select nom, lien_projet, lien_github from variantes where id_projet_origine=(select id from infos_site where nom_short=\"$projectId\")");
            $stmt->execute();

            return $stmt->fetchAll();

        }
        catch(PDOException $e) {
            print "Connection ratée". $e->getMessage();
            die();
        }

    }
    /* Gets all skills in the database for /competences */
    public function getAllSkills() {
        try {
            $em = $this->em;
            $conn = $em->getConnection();

            $stmt = $conn->executeQuery("select * from fonctionnalites");
            $stmt->execute();

            return $stmt->fetchAll();
        }

        catch(PDOException $e) {

        }
    }

    public function getProjectsWithShortDescriptions($locale) {
        try {
            $em = $this->em;
            $conn = $this->em->getConnection();

            $stmt = $conn->executeQuery("select left(description.texte, 50) as texte, infos_site.* from infos_site inner join description on description.nom_short = infos_site.nom_short where description.langue='$locale';");
            return $stmt->fetchAll();

        }

        catch(PDOException $e) {
            print "Connection ratée". $e->getMessage();
            die();
        }


    }

    public function getPersonalProjects($locale) {
        try {


            $conn = $this->em->getConnection();

            $stmt = $conn->executeQuery("SELECT nom, nom_short from infos_site where type_projet=1 order by ordre_projet desc"); // type_projet : 0 => Openclassrooms projects, 1 : personal projects
            $stmt->execute();
            $data = $stmt->fetchAll();

            if($locale != "fr") {
                $data = $this->ts->translateProjectTitles($data);
            }
            //echo serialize($data);
            return $data;

        }
        catch(PDOException $e) {
            print "Connection ratée". $e->getMessage();
            die();
        }

    }
    /* Used in the carousel */
    public function getImagesFromProjectId($projectName) {
        try {

            $conn = $this->em->getConnection();

            $stmt = $conn->executeQuery("SELECT url, placeholder from images where projet_concerne=(select id from infos_site where nom_short=\"${projectName}\") order by ordre");
            $stmt->execute();
            $data = $stmt->fetchAll();

            return $data;

        }
        catch(PDOException $e) {
            print "Connection ratée". $e->getMessage();
            die();
        }
    }

    public function getAjaxSkillsByProject($locale) {
        $conn = $this->em->getConnection();



        // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if(!isset($_GET["technos"])) { // If we want all technologies
            $query = "select left(description.texte, 50) as texte, infos_site.* from infos_site inner join description on description.nom_short = infos_site.nom_short where description.langue='$locale';";


        } else {
            $liste_technos = join(",", $_GET["technos"]);
            $query = "select left(description.texte, 50) as texte, infos_site.* from infos_site inner join description
                        on description.nom_short = infos_site.nom_short
                        where description.langue='$locale'
                        and infos_site.id in
                        (select projet_concerne from fcts_projets where id_fonctionnalite in
                        (select id from `fonctionnalites` where nom_min in ($liste_technos)))";
        }


        $stmt = $conn->prepare($query);

        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_CLASS);

        return $results;
    }
}


?>
