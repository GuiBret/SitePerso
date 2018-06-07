<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 31/01/2018
 * Time: 19:18
 */

$servername = "guillaumwjroot.mysql.db";
$username = "guillaumwjroot";
$password = "zgmfX01a";

try {
    $conn = new PDO("mysql:host=$servername;dbname=guillaumwjroot", $username, $password, array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    $locale = $_GET['locale'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(!$_GET["technos"]) { // If we want all technologies
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

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results, JSON_UNESCAPED_UNICODE);


}

catch(PDOException $e) {
    echo "Connexion ratée". $e->getMessage();
}
