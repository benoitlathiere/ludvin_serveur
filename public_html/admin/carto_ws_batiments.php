<?php
/**
* Fichier appelé par le client Android Cartographe
* pour identification utilisateur
* Projet LUDVIN 16.2.2013
*/


//on affiche les erreurs en ligne pour debug :
if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}

//on va chercher le fichier qui centralise les identifiants pour l'accès à la base :
require (realpath(".") . "/../conf/base.conf.php");


//connexion Mysql :
$res = mysql_connect($host, $user, $pass);
if (! $res)
	die("ERREUR:Problème connexion serveur!");
if (! mysql_select_db($base))
	die("ERREUR:Problème sélection base !");
mysql_set_charset("utf8");	//client sera en UTF-8


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
	
	
	switch ($_POST['action']) {
		
		/* LISTE DES BATIMENTS */
		case "CARTOGRAPHE_LISTE_BATIMENTS":
			$requete = "SELECT b.*, count(e.ID) AS nb_etages FROM batiment b LEFT JOIN etage e ON b.ID=e.batiment_ID GROUP BY b.ID ;" ;
			//echo $requete;	//debug
			$retour = @mysql_query($requete);
			if ($retour===false) {
				die("ERREUR:Problème de requête dans la base de données.");
			} else {
				$json_string = "{\"batiments\":[\n";
				if (@mysql_num_rows($retour)>0) {		//on parcourt chaque ligne du résultat
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
	
		/* NOUVEAU BATIMENT */
		case"CARTOGRAPHE_NOUVEAU_BATIMENT":
			//todo...
			if ($_POST['nom']!="") {
				//on vérifie si le bâtiment existe déjà
				$requete = "SELECT * FROM batiment WHERE nom='".mysql_real_escape_string($_POST['nom'])."' ;" ;
				//die($requete);	//debug
				$retour = mysql_query($requete);
				if (mysql_num_rows($retour)>0) {
					die("ERREUR: Le nom du bâtiment existe déjà.");
				}else{
					//on créé le bâtiment
					$requete = "INSERT INTO batiment (nom) VALUES ('".($_POST['nom'])."') ; ";
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

		/* SUPPRIMER BATIMENT */
		case"CARTOGRAPHE_SUPPRIMER_BATIMENT":
			//todo...
			if ($_POST['nom']!="") {
				//on vérifie si le bâtiment existe déjà
				$requete = "SELECT * FROM batiment WHERE nom='".mysql_real_escape_string($_POST['nom'])."' ;" ;
				die("ERREUR: ".$requete);	//debug
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
			}else {
				die("ERREUR: Le nom du bâtiment est vide.");
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
	
		/* là on sait pas ce que l'on fait..! */
		default:
			// input = "json=%7B%22locations%22%3A%5B%7B%22..."
			$input = file_get_contents('php://input');
			// jsonObj is empty, not working
			$jsonObj = json_decode($input, true);
			print_r($jsonObj);
			die("serveur : trou noir !");	//debug

	}
	
	
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {	/* Méthode GET (à proscrire...) */
	
	/* LISTE DES BATIMENTS */
	if ($_GET['action']=="LISTE_BATIMENTS") {
		$requete = "SELECT b.*, count(e.ID) AS nb_etages FROM batiment b LEFT JOIN etage e ON b.ID=e.batiment_ID GROUP BY b.ID ;" ;
		echo $requete;	//debug
		$retour = @mysql_query($requete);
		if (! $retour) {
			die("ERREUR:Problème dans la requête. ".$requete);
		} else {
			if (@mysql_num_rows($res)>0) {		//on parcourt chaque ligne du résultat
				$json_string = "{\"batiments\":[\n";
				while ($row = mysql_fetch_array($res)) {
					$json_string .= "\t{\"batiment\":{\"ID\":\"{$row['ID']}\",\"nom\":\"{$row['nom']}\",\"etages\":\"{$row['nb_etages']}\"}},\n";	//JSON syntaxe
				}
				$json_string .= "]}";
				die($json_string);
			} else {
				die("ERREUR: Aucun bâtiment trouvé dans la base de données. ".$requete);
			}
		}
	}
}
?>
