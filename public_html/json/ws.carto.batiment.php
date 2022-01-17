<?php
/**
* Fichier appelé par le WS CARTO
* Projet LUDVIN 2013
* auteurs : J. Bodard, H. Boultache, F. Laazouia, B. Lathiere
* Université Paris 8 / Master "Technologie et Handicap"
* http://handiman.univ-paris8.fr/~ludvin
*/


/* FONCTIONNEMENT :
 * Le script reçoit une commande (variable "action") et d'autres paramètres en POST,
 * 
 * Les messages verbeux renvoyés au client commencent par un mot-clé pour être ensuite traités : {ERREUR:|INFO:}
 * "OK" est simplement retourné quand l'action a été opérée avec succès.
 * /!\ ATTENTION : Tout doit être en UTF_8
 */


switch ($action) {


	case "lister":
		$requete = "SELECT b.*, count(e.ID) AS nb_etages FROM batiment b LEFT JOIN etage e ON b.ID=e.batiment_ID GROUP BY b.ID ORDER BY b.nom ASC;" ;
		$resultat = $mysqli->query($requete);
		if ($resultat===false) {
			die("ERREUR:Problème de requête dans la base de données.");
		} else {
			$json_string = "{";
				$json_string .= '"objet": "'.$objet.'",';
				$json_string .= '"action": "'.$action.'",';
				$json_string .= '"reponses":{';
					
					if ($resultat->num_rows>0) {
						$json_string .= "\"batiments\":[";
						$reponses = "\"batiments\":[";
						while ($row = $resultat->fetch_array(MYSQLI_ASSOC)) {
							$json_string .= "{\"batiment\":{\"ID\": {$row['ID']},\"nom\":\"{$row['nom']}\",\"etages\": {$row['nb_etages']}}},";	//JSON syntaxe
							$reponses .= "{\"batiment\":{\"ID\": {$row['ID']},\"nom\":\"{$row['nom']}\",\"etages\": {$row['nb_etages']}}},";	//JSON syntaxe
						}
						$json_string = substr($json_string, 0, strlen($json_string)-1);        //PATCH : enlever virgule du dernier élément
						$reponses = substr($reponses, 0, strlen($reponses)-1);        //PATCH : enlever virgule du dernier élément
						$json_string .= "]";	//fermer batiments
						$reponses .= "]";	//fermer batiments
						$json_string .= ",\"message\":\"OK\"";
						$reponses .= ",\"message\":\"OK\"";
					} else {
						$json_string .= '"message":"ERREUR","erreur":"Aucun bâtiment à supprimer."';
						$reponses .= '"message":"ERREUR","erreur":"Aucun bâtiment à supprimer."';
					}
				$json_string .= '}';	//fermer reponses
			$json_string .= '}';
			//die($json_string);	///test $json_reponse
		}
		break;
		
		
	/* NOUVEAU BATIMENT */
	case "creer":
		if ($contenu['nom'] !="") {
			$nombat = $mysqli->real_escape_string($contenu['nom']);
			//on vérifie si le bâtiment existe déjà
			//$requete = "SELECT * FROM batiment WHERE nom='".mysql_real_escape_string($_POST['nom'])."' ;" ;
			$requete = "SELECT * FROM batiment WHERE nom='".$nombat."' ;" ;
			$retour = $mysqli->query($requete);
			if ($retour->num_rows>0) {
				//TODO json erreur
				die("ERREUR: Le nom du bâtiment existe déjà.");
			} else {
				//on créé le bâtiment
				$requete = "INSERT INTO batiment (nom) VALUES ('".$nombat."') ; ";
				$retour = $mysqli->query($requete);
				$id = $mysqli->insert_id;
				if ($id>0) {
					//on créé le RDC par défaut
					$id_bat = $id;
					$requete = "INSERT INTO etage (nom, batiment_ID) VALUES ('Rez-de-chaussée', '{$id_bat}') ; ";
					$retour = $mysqli->query($requete);
					//TODO faire réposne propre
					/*if ($retour) {
						$json_string  = "{";
							$json_string .= "\"objet\":\"{$objet}\",";
							$json_string .= "\"action\":\"{$action}\",";
							$json_string .= "{\"reponses\":";
								$json_string .= "{\"ID\": {$id},\"message\":\"OK\"}";
							$json_string .= "}";
						$json_string .= "}";
						die($json_string);
					}else{
						die("ERREUR: Problème de requête pour la création du rez-de-chaussée.");
					}*/
					$json_string  = "{";
						$json_string .= "\"objet\":\"{$objet}\",";
						$json_string .= "\"action\":\"{$action}\",";
						$json_string .= "\"reponses\":";
							$json_string .= "{\"ID\": {$id},\"message\":\"OK\",\"info\":\"Le bâtiment a été créé.\"}";
					$json_string .= "}";
					die($json_string);					
				} else {
					$json_string  = "{";
						$json_string .= "\"objet\":\"{$objet}\",";
						$json_string .= "\"action\":\"{$action}\",";
						$json_string .= "\"reponses\":";
							$json_string .= "{\"ID\": 0,\"message\":\"erreur\",\"erreur\":\"Le bâtiment n'a pu être créé.\"}";
					$json_string .= "}";
					die($json_string);
				}
			}
		} else {
			$json_string  = "{";
				$json_string .= "\"objet\":\"{$objet}\",";
				$json_string .= "\"action\":\"{$action}\",";
				$json_string .= "\"reponses\":";
					$json_string .= "{\"ID\": 0,\"message\":\"erreur\",\"erreur\":\"Le nom du bâtiment est vide.\"}";
			$json_string .= "}";
			die($json_string);
		}
		break;

		
	/* SUPPRIMER BATIMENT */
	case "supprimer":
		if (intval($contenu['ID']) != "") {
			$requete = "DELETE FROM batiment WHERE ID='".intval($contenu['ID'])."' LIMIT 1 ; ";
			$resultat = $mysqli->query($requete);
			if ($resultat) {
				$json_string = "{";
					$json_string .= "\"objet\":\"{$objet}\",";
					$json_string .= "\"action\":\"{$action}\",";
					$json_string .= "\"reponses\":";
						$json_string .= "{\"ID\": ".$contenu['ID'].",\"message\":\"OK\"}";
				$json_string .= "}";
				die($json_string);
			} else {
				die("ERREUR: Problème de requête pour la suppression du bâtiment.");
			}
		} else {
			$json_string = '{';
				$json_string .= "\"objet\":\"{$objet}\",";
				$json_string .= "\"action\":\"{$action}\",";
				$json_string .= "\"reponses\":";
					$json_string .= "{\"ID\": 0,\"message\": \"erreur\",\"erreur\": \"L'identifiant est manquant.\"}";
			$json_string .= "}";
			die($json_string);
		}
		break;

		
	/* RENOMMER BATIMENT */
	case "renommer":
		if ($contenu['nom']!="" || $contenu['nouveau']!="" || $contenu['ID']!="") {
			$nombat = $mysqli->real_escape_string($contenu['nouveau']);
			$requete = "UPDATE batiment SET nom='".$nombat."' WHERE  ID='".$contenu['ID']."' LIMIT 1 ; ";
			$retour = $mysqli->query($requete);
			if ($retour) {
				$json_string = "{";
					$json_string .= "\"objet\":\"{$objet}\",";
					$json_string .= "\"action\":\"{$action}\",";
					$json_string .= "\"reponses\":";
						$json_string .= "{\"ID\": \"{$contenu['ID']}\",\"message\":\"OK\"}";
				$json_string .= "}";
				die($json_string);
			} else {
				$json_string = "{";
					$json_string .= "\"objet\":\"{$objet}\",";
					$json_string .= "\"action\":\"{$action}\",";
					$json_string .= "\"reponses\":";
						$json_string .= "{\"ID\": \"{$contenu['ID']}\",\"message\": \"erreur\",\"erreur\": \"Le changement de nom n\'a pas pu être fait.\"}";
				$json_string .= "}";
				die($json_string);
			}
		}else {
			//TDO msg json
			$json_string = '{';
				$json_string .= "\"objet\":\"{$objet}\",";
				$json_string .= "\"action\":\"{$action}\",";
				$json_string .= '"reponses":{';
					$json_string .= '"ID": "","message": "erreur","erreur": "Le nouveau ou l\'ancien noms sont manquants."';
				$json_string .= '}';
			$json_string .= "}";
			die($json_string);
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
?>