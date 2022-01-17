<?php


/* FICHIER OBSOLETE !!!! */




/**
* Fichier appelé par le client Android Cartographe
* pour identification utilisateur
* Projet LUDVIN 13.2.2013
*/

//on va chercher le fichier qui centralise les identifiants pour l'accès à la base :
require (realpath(".") . "/../conf/base.conf.php");

//on récupére les infos passées en GET :
$carto_login = $_GET["login"];
$carto_pass = $_GET["pass"];




//connexion Mysql :
if (! mysql_connect($host, $user, $pass))
	die("problème connexion serveur!");
if (! mysql_select_db($base))
	die("Problème sélection base !");
$requete = "SELECT * FROM compte WHERE email='".$carto_login."' AND password='".$carto_pass."' AND privilege='Carto' ;" ;
$res = @mysql_query($requete);
if (@mysql_num_rows($res)>0) {
	echo ("CONNEXION_CARTOGRAPHE_ACCEPTEE");	//login OK
} else {
	echo ("CONNEXION_CARTOGRAPHE_REFUSEE");		//login pas OK
}

?>
