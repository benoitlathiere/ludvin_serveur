<?php

/** --- fichier obsolete ! --- **/


/**
* Fichier appelé par le client Android Cartographe
* pour identification utilisateur
* Projet LUDVIN février 2013
* autors : J. Bodard, H. Boultache, F. Laazouia, B. Lathiere
* Université Paris 8 / Master "Technologie et Handicap"
*/


/* FONCTIONNEMENT :
 * Le script reçoit une commande (variable "action") et d'autres paramètres en POST,
 * 
 * Les messages verbeux renvoyés au client commencent par un mot-clé pour être ensuite traités : {ERREUR:|INFO:}
 * "OK" est simplement retourné quand l'action a été opérée avec succès.
 * /!\ ATTENTION : Tout doit être en UTF_8
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
mysql_set_charset("utf8");	//client sera en UTF-8, indispensable


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
		/* IDENTIFICATION */
		case "IDENTIFICATION_CARTOGRAPHE":
		
			$requete = "SELECT ID FROM compte WHERE email='".mysql_real_escape_string($_POST["login"])."' AND password='".mysql_real_escape_string($_POST["pass"])."'  ;" ;
			$resultat = @mysql_query($requete);
			if (@mysql_num_rows($resultat)>0) {
				$retour = mysql_fetch_assoc($resultat);
				$json_string  = "{\n";
				$json_string .= "\t\"reponse\": \"IDENTIFICATION_ACCEPTEE\", \n";
				$json_string .= "\t\"ID\": ".$retour['ID']."\n";
				$json_string .= "}";
                                 die($json_string);
			} else {
				$json_string  = "{\n";
                                $json_string .= "\t\"reponse\": \"IDENTIFICATION_REFUSEE\"\n";
                                $json_string .= "\n}";
				die($json_string);		//login pas OK
			}
			break;
				
				
		/* LISTE DES BATIMENTS */
		case "CARTOGRAPHE_LISTE_BATIMENTS":
			$requete = "SELECT b.*, count(e.ID) AS nb_etages FROM batiment b LEFT JOIN etage e ON b.ID=e.batiment_ID GROUP BY b.ID ORDER BY b.nom ASC;" ;
			$retour = @mysql_query($requete);
			if ($retour===false) {
				die("ERREUR:Problème de requête dans la base de données.");
			} else {
				$json_string = "{\"batiments\":[\n";
				if (@mysql_num_rows($retour)>0) {
					while ($row = mysql_fetch_array($retour)) {
						$json_string .= "\t{\"batiment\":{\"ID\": {$row['ID']},\"nom\":\"{$row['nom']}\",\"etages\": {$row['nb_etages']}}},\n";	//JSON syntaxe
					}
					$json_string = substr($json_string, 0, strlen($json_string)-2 );        //PATCH : enlever virgule et CRLF du dernier élément
					$json_string .= "]}";
					die($json_string);
				} else {
					die("ERREUR: Aucun bâtiment trouvé dans la base de données.");
				}
			}
			break;
			

		/* LISTE DES ETAGES */
		case "CARTOGRAPHE_LISTE_ETAGES":
			$requete = "SELECT e.*, count(r.ID) AS nb_reperes FROM etage e LEFT JOIN repere r ON e.ID=r.etage_ID ". (intval($_POST['batiment_ID'])!=""? " WHERE batiment_ID='".intval($_POST['batiment_ID'])."' " :"") ." GROUP BY e.ID ORDER BY e.nom ASC ;" ;
			//echo $requete;	//debug
			$retour = @mysql_query($requete);
			if ($retour===false) {
				die("ERREUR:Problème de requête sur la liste des étages.");
			} else {
				$json_string = "{\"etages\":[";
				if (@mysql_num_rows($retour)>0) {		//on parcourt chaque ligne du résultat
					//$tabjson[]=null;
					while ($row = mysql_fetch_array($retour)) {
						$json_string .= "\t{\"etage\":{\"ID\": {$row['ID']},\"nom\":\"{$row['nom']}\",\"reperes\": {$row['nb_reperes']},\"batiment_ID\": {$row['batiment_ID']}}},";	//JSON syntaxe
						//$tabjson[] = "\t{\"etage\":{\"ID\": {$row['ID']},\"nom\":\"{$row['nom']}\",\"reperes\": {$row['nb_reperes']},\"batiment_ID\": {$row['batiment_ID']}}},\n";	//JSON syntaxe
					}
					//$json_string .= implode(",",$tabjson);	//bugs
					$json_string = substr($json_string, 0, strlen($json_string)-1 );	//PATCH : enlever virgule et CRLF du dernier élément
					$json_string .= "]}";
					die($json_string);
				} else {
					die("ERREUR: Aucun étage trouvé dans la base de données.");
				}
			}
			break;

		/* LISTE DES REPERES */
		case "CARTOGRAPHE_LISTE_REPERES":
			$requete = "SELECT r.* FROM repere r ". ($_POST['etage_ID']!=""? " WHERE etage_ID='".intval($_POST['etage_ID'])."' " :"") ."  ORDER BY r.nom ASC ;" ;
			//die("ERREUR:".$requete);	//debug
			$retour = @mysql_query($requete);
			if ($retour===false) {
				die("ERREUR:Problème de requête sur la liste des repères.");
			} else {
				$json_string = "{\"reperes\":[\n";
				if (@mysql_num_rows($retour)>0) {		//on parcourt chaque ligne du résultat
					while ($row = mysql_fetch_array($retour)) {
						$json_string .= "\t{\"repere\":{\"ID\": {$row['ID']},\"nom\":\"{$row['nom']}\",\"etage_ID\": {$row['etage_ID']}}},\n";	//JSON syntaxe
					}
					$json_string = substr($json_string, 0, strlen($json_string)-2 );        //PATCH : enlever virgule et CRLF du dernier élément
					$json_string .= "]}";
					die($json_string);
				} else {
					die("ERREUR: Aucun repère trouvé dans la base de données.");
				}
			}
			break;

			
			

		/* LISTE DES POSITIONS */
		case "CARTOGRAPHE_LISTE_POSITIONS":
			$requete = "SELECT count(*) AS nb, email, SUBSTRING(m.date FROM 1 FOR 10) AS datejour, fk_repere AS repere  FROM mesures AS m LEFT OUTER JOIN compte AS c ON (m.fk_compte=c.ID) WHERE fk_repere='".intval($_POST['repere_ID'])."'  GROUP BY fk_compte, SUBSTRING(m.date FROM 1 FOR 10) ORDER BY SUBSTRING(m.date FROM 1 FOR 10) DESC ;" ;
			//die("ERREUR:".$requete);	//debug
			$retour = @mysql_query($requete);
			if ($retour===false) {
				die("ERREUR:Problème de requête sur la liste des positions.");
			} else {
				$json_string = "{\"positions\":[\n";
				if (@mysql_num_rows($retour)>0) {		//on parcourt chaque ligne du résultat
					while ($row = mysql_fetch_array($retour)) {
						$json_string .= "\t{\"position\":{" ;
						$json_string .= "\"compte\":\"{$row['email']}\",";
						$json_string .= "\"nb\": {$row['nb']},";
						$json_string .= "\"datejour\":\"{$row['datejour']}\",";
						$json_string .= "\"repere\": {$row['repere']}";
						$json_string .= "}},\n";
					}
					$json_string = substr($json_string, 0, strlen($json_string)-2 );        //PATCH : enlever virgule et CRLF du dernier élément
					$json_string .= "]}";
					die($json_string);
				} else {
					die("ERREUR: Aucune position trouvée dans la base de données.");
				}
			}
			break;




                /* LISTE DES MESURES */
		//copié de "Liste des postions...
                case "CARTOGRAPHE_LISTE_MESURES":
			if (intval($_POST['repere_ID'])!="") {
				$requete = "SELECT * FROM mesuresreperejour WHERE idrepere='".intval($_POST['repere_ID'])."' ORDER BY jour DESC, nb DESC;" ;
                	        //die("DEBUG:".$requete);      //debug
                        	$retour = @mysql_query($requete);
	                        if ($retour===false) {
        	                        die("ERREUR:Problème de requête sur la liste des mesures : ".$requete);
                	        } else {
                        	        $json_string = "{\"mesures\":[\n";
                                	if (@mysql_num_rows($retour)>0) {               //on parcourt chaque ligne du résultat
                                        	while ($row = mysql_fetch_array($retour)) {
                                                	$json_string .= "\t{\"mesure\":{" ;
	                                                $json_string .= "\"email\":\"{$row['email']}\"," ;
	                                                $json_string .= "\"nom\":\"{$row['nom']}\"," ;
                        	                        $json_string .= "\"idcompte\": {$row['idcompte']},";
        	                                        $json_string .= "\"nb\": {$row['nb']},";
                	                                $json_string .= "\"jour\":\"{$row['jour']}\",";
                        	                        $json_string .= "\"idrepere\": {$row['idrepere']}";
                                	                $json_string .= "}},\n";
                                        	}
						$json_string = substr($json_string, 0, strlen($json_string)-2 );        //PATCH : enlever virgule et CRLF du dernier élément
	                                        $json_string .= "]}";
        	                                die($json_string);
                	                } else {
                        	                die("ERREUR: Aucune mesure trouvée dans la base de données.");
                                	}
	                        }
			} else {
				die("ERREUR:Problème de requête sur la liste des mesures (repere_ID absent).");
			}
                        break;

			
			
		/* NOUVEAU BATIMENT */
		case "CARTOGRAPHE_NOUVEAU_BATIMENT":
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
					$id=mysql_insert_id();
					if ($retour) {
						//on créé le RDC par défaut
						$id_bat = mysql_insert_id();
						$requete = "INSERT INTO etage (nom, batiment_ID) VALUES ('Rez-de-chaussée', '{$id_bat}') ; ";
						$retour = mysql_query($requete);
						if ($retour) {
							//die("OK");
							$json_string = "{\"reponses\":[\n";
							$json_string .= "{\"reponse\":{\"ID\":\ $id},\"message\":\"OK\"}}";
							$json_string .= "]}";
							die($json_string);
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
		case "CARTOGRAPHE_NOUVEAU_ETAGE":
			if ($_POST['nom']!="" && $_POST['batiment_ID']!="") {
				//on vérifie si l'étage existe déjà
				$requete = "SELECT * FROM etage WHERE nom='".mysql_real_escape_string($_POST['nom'])."' AND batiment_ID='".mysql_real_escape_string($_POST['batiment_ID'])."' ;" ;
				//die($requete);	//debug
				$retour = mysql_query($requete);
				if (mysql_num_rows($retour)>0) {
					die("ERREUR: Le nom de l'étage existe déjà pour cet étage.");
				}else{
					//on créé l'élément
					$requete = "INSERT INTO etage (nom, batiment_ID) VALUES ('".mysql_real_escape_string($_POST['nom'])."', '".mysql_real_escape_string($_POST['batiment_ID'])."') ; ";
					$retour = mysql_query($requete);
					$id=mysql_insert_id();
					if ($retour) {
						//die("OK");
						$json_string = "{\"reponses\":[\n";
						$json_string .= "{\"reponse\":{\"ID\": {$id},\"message\":\"OK\"}}";
						$json_string .= "]}";
						die($json_string);
					}else{
						die("ERREUR: Problème de requête pour la création de l'étage : ");
					}
				}
			}else {
				die("ERREUR: Le bâtiment n'existe pas ou le nom de l'étage est vide.");
			}
			break;

			
		/* NOUVEAU REPERE */
		case "CARTOGRAPHE_NOUVEAU_REPERE":
			if ($_POST['nom']!="" && $_POST['etage_ID']!="") {
				//on vérifie si le repère existe déjà
				$requete = "SELECT * FROM repere WHERE nom='".mysql_real_escape_string($_POST['nom'])."' AND etage_ID='".mysql_real_escape_string($_POST['etage_ID'])."' ;" ;
				$retour = mysql_query($requete);
				if (mysql_num_rows($retour)>0) {
					die("ERREUR: Le nom du repère existe déjà pour cet étage.");
				}else{
					//on créé le repère
					$requete = "INSERT INTO repere (nom, etage_ID, poi, urgence, commentaire) VALUES ('".mysql_real_escape_string($_POST['nom'])."', '".intval($_POST['etage_ID'])."', '".intval($_POST['poi'])."', '".intval($_POST['urgence'])."', '".mysql_real_escape_string($_POST['commentaire'])."'  ) ; ";
					$retour = mysql_query($requete);
					$id=mysql_insert_id();
					if ($retour) {
						//die("OK;ID=".$id);
						$json_string = "{\"reponses\":[\n";
						$json_string .= "{\"reponse\":{\"ID\": {$id},\"message\":\"OK\"}}";
						$json_string .= "]}";
						die($json_string);
					}else{
						die("ERREUR: Problème de requête pour la création du repère.");
					}
				}
			}else {
				die("ERREUR: Le niveau n'existe pas ou le nom du repère est vide.");
			}
			break;

			
		/* SUPPRIMER BATIMENT */
		case "CARTOGRAPHE_SUPPRIMER_BATIMENT":
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
			}else {
				die("ERREUR: Le nom du bâtiment est vide.");
			}
			break;

			
		/* SUPPRIMER ETAGE */
		case "CARTOGRAPHE_SUPPRIMER_ETAGE":
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


			
		/* SUPPRIMER REPERE */
		case "CARTOGRAPHE_SUPPRIMER_REPERE":
			if ($_POST['ID']!="") {
				//on vérifie si le repère existe déjà
				$requete = "SELECT * FROM repere WHERE ID='".intval($_POST['ID'])."' ;" ;
				$retour = mysql_query($requete);
				if (mysql_num_rows($retour)==0) {
					die("ERREUR: Le repère \"{$_POST['nom']}\" n'existe pas.");
				}else{
					//on supprime le repère
					$requete = "DELETE FROM repere WHERE ID='".intval($_POST['ID'])."' LIMIT 1 ; ";
					$retour = mysql_query($requete);
					if ($retour) {
						die("OK");
					}else{
						die("ERREUR: Problème de requête pour la suppression du repère.");
					}
				}
			}else {
				die("ERREUR: L'identifiant du repère est vide.");
			}
			break;			

		
                /* SUPPRIMER MESURES */			//A FINIR !!!!
                case "CARTOGRAPHE_SUPPRIMER_MESURES":
                        if ($_POST['jour']!="" && $_POST['auteur']!="" && intval($_POST['idrepere'])) {
                                //on vérifie si le repère existe déjà
                                $requete = "SELECT * FROM compte WHERE email='".$_POST['auteur']."' ;" ;
                                //die($requete);	//debug
				$resultat = mysql_query($requete);
				$auteur = mysql_fetch_assoc($resultat);
                                //if (mysql_num_rows($retour)==0) {
                                //        die("ERREUR: Le repère \"{$_POST['nom']}\" n'existe pas.");
                                //}else{
                                        //on supprime 
                                        $requete = "DELETE FROM mesures WHERE fk_repere='".intval($_POST['idrepere'])."' AND fk_compte='".$auteur['ID']."' AND SUBSTRING(date FROM 1 FOR 10)='".$_POST['jour']."' ; ";
					//die($requete);	//debug
                                        $retour = mysql_query($requete);
                                        if ($retour) {
                                                die("OK");
                                        }else{
                                                die("ERREUR: Problème de requête pour la suppression des mesures.");
                                        }
                                //}
                        }else {
                                die("ERREUR: Des infos sont manquantes (jour|auteur|idrepere) pour supprimer les mesures.");
                        }
                        break;
	
			
		/* RENOMMER BATIMENT */
		case "CARTOGRAPHE_RENOMMER_BATIMENT":
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
		case "CARTOGRAPHE_RENOMMER_ETAGE":
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

	
		
		/* RENOMMER REPERE */
		case "CARTOGRAPHE_RENOMMER_REPERE":
			if ($_POST['nouveau']!="" && $_POST['ID']!="") {
				$requete = "UPDATE repere SET nom='".mysql_real_escape_string($_POST['nouveau'])."' WHERE ID='".intval($_POST['ID'])."' LIMIT 1 ; ";
				$retour = mysql_query($requete);
				if ($retour)
					die("OK");
				else
					die("ERREUR: Problème de requête pour renomemr le repère.". $requete);
			}else {
				die("ERREUR: Nom ou identifiant du repère vide.");
			}
			break;			
	
	
	
		/* là on ne sait pas ce que l'on fait..! */
		default:
			// input = "json=%7B%22locations%22%3A%5B%7B%22..."
			$input = file_get_contents('php://input');
			// jsonObj is empty, not working
			$jsonObj = json_decode($input, true);
			print_r($jsonObj);
			die("ERREUR: trou noir ! Pas d'action trouvée !");	//debug

	}
	
	
} 

?>
