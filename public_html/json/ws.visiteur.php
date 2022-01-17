<?php

/***
 ** Fichier principal appelé par le client Android Visiteur
 **
 ** Projet LUD'VIN février - juin 2013
 ** Auteurs : J. Bodard, H. Boultache, F. Laazioua, B. Lathiere
 ** Université Paris 8 / Master "Technologie et Handicap"
 ** http://handiman.univ-paris8.fr/~ludvin/
 */

//on affiche les erreurs en ligne pour debug :
ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_NOTICE);

//on va chercher le fichier qui centralise les identifiants pour l'accès à la base :
require realpath(".") . "/../conf/base.conf.php";
require realpath(".") . "/localisation.php";

//connexion Mysql version objet	
$db = new mysqli($host, $user, $pass, $base);
if (mysqli_connect_error()) {
  die('Connect error ('.mysqli_connect_errno().')'.mysqli_connect_error());
}

$json = file_get_contents('php://input');
$json = json_decode($json, true);

$cmd = $json['commande'];
$bornes = $json['bornes'];

switch ($cmd) {
 case 'localisation':
   locate($bornes, $db);
   break;
 case 'ALL':
   print_pois($bornes, $db);
   break;
 case 'TOILETTES':
   echo "Commande POI: toilettes\n";
   break;
 case 'ASCENSEUR':
   echo "Commande POI: ascenseur\n";
   break;
 case 'ESCALIER':
   echo "Commande POI: escalier\n";
   break;
 default:
   echo "Commande inconnue\n";
}

$db->close();

?>
