<?php


/* 2/3/2013 : FICHIER OBSOLETE !!!! prendre ludvin_ws_carto.php */











/**
* Fichier appelé par le client Android Cartographe
* pour identification utilisateur
* Projet LUDVIN février2013 / Master Handi / Université Paris 8
* autors : J. Bodard, H. Boulache, F. Laazouia, B. Lathiere
*/


/* *** FONCTIONNEMENT ***
 * Le script reçoit une commande (variable "action") et d'autres paramètres en POST,
 *
 
 * Les messages verbeux renvoyés au client commencent par un mot-clé pour être ensuite traités {ERREUR:|INFO:}
 * "OK" est simplement retourné quand l'action a été opérée avec succès.
 * /!\ ATTENTION : Tout doit être traité en UTF_8
 */
 
 

//on affiche les erreurs en ligne pour debug :
ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_NOTICE);


//on va chercher le fichier qui centralise les identifiants pour l'accès à la base :
require (realpath(".") . "/../conf/base.conf.php");


//connexion Mysql :
$res = mysql_connect($host, $user, $pass);
if (! $res)
	die("ERREUR:Problème connexion serveur!");
if (! mysql_select_db($base))
	die("ERREUR:Problème sélection base !");

mysql_set_charset("utf8");	//le client sera en UTF-8, indispensable


//debugs données reçues, activer si besoin :
/*
echo "\nrequete:".$_SERVER['REQUEST_METHOD'];
print_r("\n\$_POST: ".$_POST); 
print_r("\n\$_GET: ".$_GET); 
print_r("\n\$_REQUEST: ".$_REQUEST); 
$postdata = file_get_contents("php://input");
print_r("\npostdata: ".$postdata);
echo("\nencodage client:".mysql_client_encoding());
echo("\n");
*/

/* Web Service en POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	//debug :
	//foreach ($_POST as $key => $value) {echo "debug post: ".$key." -> ".$value."\n";}
	
	
	switch (mysql_real_escape_string($_POST['action'])) {
	
	
		/* IDENTIFICATION */
		case "IDENTIFICATION_CARTOGRAPHE":
			$requete = "SELECT * FROM compte WHERE email='".mysql_real_escape_string($_POST["login"])."' AND password='".mysql_real_escape_string($_POST["pass"])."' AND privilege='Carto' ;" ;
			$retour = @mysql_query($requete);
			if (@mysql_num_rows($retour)>0)
				die("CONNEXION_CARTOGRAPHE_ACCEPTEE");	//login OK
			else
				die("CONNEXION_CARTOGRAPHE_REFUSEE");		//login pas OK
				
		
		/* LISTE DES BATIMENTS */
		case "CARTOGRAPHE_LISTE_BATIMENTS":
			$requete = "SELECT b.*, count(e.ID) AS nb_etages FROM batiment b LEFT JOIN etage e ON b.ID=e.batiment_ID GROUP BY b.ID ;" ;
			$retour = @mysql_query($requete);
			if ($retour===false) {
				die("ERREUR:Problème de requête dans la base de données.");
			} else {
				$json_string = "{\"batiments\":[\n";
				if (@mysql_num_rows($retour)>0) {
					while ($row = mysql_fetch_array($retour)) {
						$json_string .= "\t{\"batiment\":{\"ID\":\"{$row['ID']}\",\"nom\":\"{$row['nom']}\",\"etages\":\"{$row['nb_etages']}\"}},\n";	//JSON syntaxe
					}
					$json_string .= "]}";
					die($json_string);
				} else {
					die("ERREUR: Aucun bâtiment trouvé dans la base de données.");
				}
			}
			break;

		/* LISTE DES ETAGES */
		case "CARTOGRAPHE_LISTE_ETAGES":
			$requete = "SELECT e.*, count(r.ID) AS nb_reperes FROM etage e LEFT JOIN repere r ON e.ID=r.etage_ID ". (intval($_POST['batiment_ID'])!=""? " WHERE batiment_ID='".intval($_POST['batiment_ID'])."' " :"") ." GROUP BY e.ID ;" ;
			//echo $requete;	//debug
			$retour = @mysql_query($requete);
			if ($retour===false) {
				die("ERREUR:Problème de requête sur la liste des étages.");
			} else {
				$json_string = "{\"etages\":[\n";
				if (@mysql_num_rows($retour)>0) {		//on parcourt chaque ligne du résultat
					while ($row = mysql_fetch_array($retour)) {
						$json_string .= "\t{\"etage\":{\"ID\":\"{$row['ID']}\",\"nom\":\"{$row['nom']}\",\"reperes\":\"{$row['nb_reperes']}\",\"batiment_ID\":\"{$row['batiment_ID']}\"}},\n";	//JSON syntaxe
					}
					$json_string .= "]}";
					die($json_string);
				} else {
					die("ERREUR: Aucun étage trouvé dans la base de données.");
				}
			}
			break;

		/* LISTE DES REPERES */
		case "CARTOGRAPHE_LISTE_REPERES":
			$requete = "SELECT r.* FROM repere r ". ($_POST['etage_ID']!=""? " WHERE etage_ID='".intval($_POST['etage_ID'])."' " :"") ."  ;" ;
			//die("ERREUR:".$requete);	//debug
			$retour = @mysql_query($requete);
			if ($retour===false) {
				die("ERREUR:Problème de requête sur la liste des repères.");
			} else {
				$json_string = "{\"reperes\":[\n";
				if (@mysql_num_rows($retour)>0) {		//on parcourt chaque ligne du résultat
					while ($row = mysql_fetch_array($retour)) {
						$json_string .= "\t{\"repere\":{\"ID\":\"{$row['ID']}\",\"nom\":\"{$row['nom']}\",\"etage_ID\":\"{$row['etage_ID']}\"}},\n";	//JSON syntaxe
					}
					$json_string .= "]}";
					die($json_string);
				} else {
					die("ERREUR: Aucun repère trouvé dans la base de données.");
				}
			}
			break;

			
		/* NOUVEAU BATIMENT */
		case"CARTOGRAPHE_NOUVEAU_BATIMENT":
			//todo...
			if ($_POST['nom']!="") {
				//on vérifie si le bâtiment existe déjà
				$requete = "SELECT * FROM batiment WHERE nom='".mysql_real_escape_string($_POST['nom'])."' ;" ;
				$retour = mysql_query($requete);
				if (mysql_num_rows($retour)>0) {
					die("ERREUR: Le nom du bâtiment existe déjà.");
				}else{
					//on créé le bâtiment
					$requete = "INSERT INTO batiment (nom) VALUES ('".mysql_real_escape_string($_POST['nom'])."') ; ";
					$retour = mysql_query($requete);
					if ($retour) {
						//on créé le RDC par défaut
						$id_bat = mysql_insert_id();
						$requete = "INSERT INTO etage (nom, batiment_ID) VALUES ('Rez-de-chaussée', '{$id_bat}') ; ";
						$retour = mysql_query($requete);
						if ($retour) {
							die("OK");
						}else{
							die("ERREUR: Problème de requête pour la création du rez-de-chaussée.");
						}
					}else{
						die("ERREUR: Problème de requête por la création du bâtiment.");
					}
				}
			}else {
				die("ERREUR: Le nom du bâtiment est vide.");
			}
			break;

			
		/* NOUVEAU ETAGE */
		case"CARTOGRAPHE_NOUVEAU_ETAGE":
			if ($_POST['nom']!="" && $_POST['batiment_ID']!="") {
				//on vérifie si l'étage existe déjà
				$requete = "SELECT * FROM etage WHERE nom='".mysql_real_escape_string($_POST['nom'])."' AND batiment_ID='".mysql_real_escape_string($_POST['batiment_ID'])."' ;" ;
				$retour = mysql_query($requete);
				if (mysql_num_rows($retour)>0) {
					die("ERREUR: Le nom de l'étage existe déjà pour ce bâtiment.");
				}else{
					$requete = "INSERT INTO etage (nom, batiment_ID) VALUES ('".mysql_real_escape_string($_POST['nom'])."', '".mysql_real_escape_string($_POST['batiment_ID'])."') ; ";
					$retour = mysql_query($requete);
					if ($retour) {
						die("OK");
					}else{
						die("ERREUR: Problème de requête pour la création de l'étage.");
					}
				}
			}else {
				die("ERREUR: Le bâtiment n'existe pas ou le nom de l'étage est vide.");
			}
			break;

			
		/* SUPPRIMER BATIMENT */
		case"CARTOGRAPHE_SUPPRIMER_BATIMENT":
			if ($_POST['nom']!="") {
				//on vérifie si le bâtiment existe déjà
				$requete = "SELECT * FROM batiment WHERE nom='".mysql_real_escape_string($_POST['nom'])."' ;" ;
				$retour = mysql_query($requete);
				if (mysql_num_rows($retour)==0) {
					die("ERREUR: Le bâtiment \"{$_POST['nom']}\" n'existe pas.");
				}else{
					//on supprime le bâtiment
					$requete = "DELETE FROM batiment WHERE nom='".mysql_real_escape_string($_POST['nom'])."' LIMIT 1 ; ";
					$retour = mysql_query($requete);
					if ($retour)
						die("OK");
					else
						die("ERREUR: Problème de requête pour la suppression du bâtiment.");
				}
			} else {
				die("ERREUR: Le nom du bâtiment est vide.");
			}
			break;

			
		/* SUPPRIMER ETAGE */
		case"CARTOGRAPHE_SUPPRIMER_ETAGE":
			if ($_POST['ID']!="") {
				//on vérifie si l'étage existe déjà
				$requete = "SELECT * FROM etage WHERE ID='".intval($_POST['ID'])."' ;" ;
				$retour = mysql_query($requete);
				if (mysql_num_rows($retour)==0) {
					die("ERREUR: L'étage \"{$_POST['nom']}\" n'existe pas.");
				}else{
					//on supprime l'étage
					$requete = "DELETE FROM etage WHERE ID='".intval($_POST['ID'])."' LIMIT 1 ; ";
					$retour = mysql_query($requete);
					if ($retour)
						die("OK");
					else
						die("ERREUR: Problème de requête pour la suppression de l'étage.");
				}
			}else {
				die("ERREUR: L'identifiant de l'étage est vide.");
			}
			break;
			

		/* RENOMMER BATIMENT */
		case"CARTOGRAPHE_RENOMMER_BATIMENT":
			if ($_POST['nom']!="" || $_POST['nouveau']!="") {
				$requete = "UPDATE batiment SET nom='".mysql_real_escape_string($_POST['nouveau'])."' WHERE nom='".mysql_real_escape_string($_POST['nom'])."' LIMIT 1 ; ";
				$retour = mysql_query($requete);
				if ($retour)
					die("OK");
				else
					die("ERREUR: Problème de requête sur le serveur.". $requete);
			}else {
				die("ERREUR: Nom de bâtiment vide.");
			}
			break;
	
		
		/* RENOMMER ETAGE */
		case"CARTOGRAPHE_RENOMMER_ETAGE":
			if ($_POST['nom']!="" && $_POST['nouveau']!="" && $_POST['ID']!="") {
				$requete = "UPDATE etage SET nom='".mysql_real_escape_string($_POST['nouveau'])."' WHERE ID='".intval($_POST['ID'])."' LIMIT 1 ; ";
				$retour = mysql_query($requete);
				if ($retour)
					die("OK");
				else
					die("ERREUR: Problème de requête pour renomemr l'étage.". $requete);
			}else {
				die("ERREUR: Nom ou identifiant d'étage vide.");
			}
			break;
	
	
		/* là on sait pas ce que l'on fait..! */
		default:
			// input = "json=%7B%22locations%22%3A%5B%7B%22..."
			$input = file_get_contents('php://input');
			// jsonObj is empty, not working
			$jsonObj = json_decode($input, true);
			print_r($jsonObj);
			die("serveur : trou noir !");	//debug

	}
	

}

die("ERREUR:Aucune action à réaliser !");

?>
