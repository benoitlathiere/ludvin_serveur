<?php

/******************     AVERTISSEMENT SPECIAL    *************************/
/* SCRIPT adapté pour la version de démonstration de LUD'VIN Cartographe */
/************** La base de données est "ludvin_demo" *********************/
/*************************************************************************/


/**************************************/
/***  SCRIPT EN COURS DE REDACTION  ***/
/***  fichier principal qui 	    ***/
/***  appelle d'autres fichiers 	***/
/***  selon le besoin.				***/
/**************************************/


/**
* Fichier principal appelé par le client Android Cartographe.
* 
* Projet LUD'VIN février - juin 2013 - www.ludvin.org
* auteurs : J. Bodard, H. Boultache, F. Laazouia, B. Lathiere
* Université Paris 8 / Master "Technologie et Handicap"
*/


/* FONCTIONNEMENT :
 * Le script reçoit un flux formaté en JSON,
 * 
 * Les messages verbeux renvoyés au client commencent par un mot-clé pour être ensuite traités : {ERREUR:|INFO:}
 * "OK" est simplement retourné quand l'action a été opérée avec succès.
 * /!\ ATTENTION : Tout doit être en UTF_8
 */

//on affiche les erreurs en ligne pour debug :
ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_NOTICE);

//on va chercher le fichier qui centralise les identifiants pour l'accès à la base :
require realpath(".") . "/../conf/base.conf.php";


//connexion Mysql version objet	
$mysqli = new mysqli($host, $user, $pass, "ludvin_demo");
if ($mysqli->connect_error)
    die('Connect Error ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
$mysqli->set_charset("utf8");	//obligatoire


//source : http://www.codeproject.com/Articles/267023/Send-and-receive-json-between-android-and-php
//JSON reçu :
$json = file_get_contents('php://input');
$json = json_decode($json, true);	//tableau associatif
//variables globales
$objet = $json['objet'];		// doit être au singulier : identification|batiment|niveau|repere|mesure
$action = $json['action'];		// liste : identifier|lister|creer|supprimer|enregistrer|renommer
$user_id = intval($json['user_id']);	// entier
$contenu = $json['contenu'];	//(optionnel) si enregistrements de données dans la base, array[]


//JSON de réponse
$json_reponse  = "{";							//ouvrir global
$json_reponse .= "\"objet\":\"{$objet}\",";
$json_reponse .= "\"action\":\"{$action}\",";
$json_reponse .= "\"user_id\":\"{$user_id}\",";
	$date = new DateTime();
$json_reponse .= "\"date\":".$date->getTimestamp().",";	//date
$json_reponse .= "\"reponses\":{";				//ouvrir reponses


//on doit récupere $reponses pour chaque action :
$reponses="";

switch ($objet) {
	case "identification";
		include "ws.carto.{$objet}.php";
		break;
	case "batiment":
		include "ws.carto.{$objet}.php";
		break;
	case "niveau":
		include "ws.carto.{$objet}.php";
		break;
	case "mesure":
		include "ws.carto.{$objet}.php";
		break;
	case "repere":
		include "ws.carto.{$objet}.php";
		break;
	default:
		die("Erreur objet");
}


$json_reponse .= $reponses;		//ajouter reponses
$json_reponse .= "}";			//fermer reponses
$json_reponse .= "}";			//fermer global
die($json_reponse);				//on envoie la réponse au client
?>