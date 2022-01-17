<?php
/**
* Fichier appelé par le WS CARTO
* Projet LUDVIN 2013 - www.ludvin.org
* autors : J. Bodard, H. Boultache, F. Laazouia, B. Lathiere
* Université Paris 8 / Master "Technologie et Handicap"
*/


switch ($action) {


	/* LISTE DES REPERES */
	case "lister":
		$requete = "SELECT r.* FROM repere r WHERE etage_ID='".intval($contenu['niveau_ID'])."' ORDER BY r.nom ASC ;" ;
		$resultat = $mysqli->query($requete);
		if ($resultat===false) {
					$reponses .= "\"ID\": 0,\"message\":\"erreur\",\"erreur\":\"Problème sur la liste des repères.\",\"debug\":\"{$requete}\"";
		} else {
			$reponses .= "\"nb\":".$resultat->num_rows.",";
			if ($resultat->num_rows>0) {
				$reponses .= "\"reperes\":[";
				while ($row = $resultat->fetch_array(MYSQLI_ASSOC)) {
					$reponses .= "{\"repere\":{\"ID\": {$row['ID']},\"nom\":\"".$mysqli->real_escape_string($row['nom'])."\",\"niveau_ID\": {$row['etage_ID']}, \"type\": {$row['poi']} }},";	//JSON syntaxe
				}
				$reponses = substr($reponses, 0, strlen($reponses)-1);        //PATCH : enlever virgule  du dernier élément
				$reponses .= "]";
				$reponses .= ",\"message\":\"OK\",\"debug\":\"{$requete}\"";
			} else {
				$reponses .= "\"message\":\"OK\",\"debug\":\"Aucun repère à lister.\"";
			}
		}
		break;

		

		
	/* NOUVEAU REPERE */
	case "creer":
		if ($contenu['nom']!="" && intval($contenu['niveau_ID'])!="") {
			$nom = $mysqli->real_escape_string($contenu['nom']);
			$commentaire = $mysqli->real_escape_string($contenu['commentaire']);
			//on vérifie si le nom existe déjà
			$requete = "SELECT * FROM repere WHERE nom='".$nom."' AND etage_ID='".intval($_POST['niveau_ID'])."' ;" ;
			$resultat = $mysqli->query($requete);
			if ($resultat->num_rows>0) {
				$json_string  = "{";
					$json_string .= "\"objet\":\"{$objet}\",";
					$json_string .= "\"action\":\"{$action}\",";
					$json_string .= "\"reponses\":";
						$json_string .= "{\"ID\": 0,\"message\":\"erreur\",\"erreur\":\"Le repère {$nom} existe déjà.\"}";
				$json_string .= "}";
				die($json_string);
			} else {
				$requete = "INSERT INTO repere (nom, etage_ID, poi, urgence, commentaire) VALUES ('".$nom."', '".intval($contenu['niveau_ID'])."', '".intval($contenu['poi'])."', '".intval($contenu['urgence'])."', '".$comentaire."'  ) ; ";
				$resultat = $mysqli->query($requete);
				$id = $mysqli->insert_id;
				if ($id>0) {
					$json_string  = "{";
						$json_string .= "\"objet\":\"{$objet}\",";
						$json_string .= "\"action\":\"{$action}\",";
						$json_string .= "\"reponses\":";
							$json_string .= "{\"ID\": {$id},\"message\":\"OK\",\"info\":\"Le repère a été créé.\"}";
					$json_string .= "}";
					die($json_string);
				}else{
					$json_string  = "{";
						$json_string .= "\"objet\":\"{$objet}\",";
						$json_string .= "\"action\":\"{$action}\",";
						$json_string .= "\"reponses\":";
							$json_string .= "{\"ID\": 0,\"message\":\"erreur\",\"erreur\":\"Le repère n'a pu être créé.\",\"debug\": \"{$requete}\"}";
					$json_string .= "}";
					die($json_string);
				}
			}
		} else {
			$json_string  = "{";
				$json_string .= "\"objet\":\"{$objet}\",";
				$json_string .= "\"action\":\"{$action}\",";
				$json_string .= "\"reponses\":";
					$json_string .= "{\"ID\": 0,\"message\":\"erreur\",\"erreur\":\"L'identifiant du repère ou le nom du bâtiment est vide.\"}";
			$json_string .= "}";
			die($json_string);
		}
		break;


		
		
		
	/* SUPPRIMER REPERE */
	case "supprimer":
		if (intval($contenu['ID'])!=0) {
			//on supprime le niveau
			$requete = "DELETE FROM repere WHERE ID='".intval($contenu['ID'])."' LIMIT 1 ; ";
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
				$json_string = '{';
					$json_string .= "\"objet\":\"{$objet}\",";
					$json_string .= "\"action\":\"{$action}\",";
					$json_string .= "\"reponses\":";
						$json_string .= "{\"ID\": 0,\"message\": \"erreur\",\"erreur\": \"Problème lors de la suppression du repère.\",\"debug\":\"{$requete}\"}";
				$json_string .= "}";
				die($json_string);
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


	
	/* RENOMMER NIVEAU */
	case "renommer":
		if ($contenu['nouveau']!="" && $contenu['ID']!="") {
			$nom = $mysqli->real_escape_string($contenu['nouveau']);
			$requete = "UPDATE repere SET nom='".$nom."' WHERE ID='".intval($contenu['ID'])."' LIMIT 1 ; ";
			$resultat = $mysqli->query($requete);
			if ($resultat) {
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
						$json_string .= "{\"ID\": \"{$contenu['ID']}\",\"message\": \"erreur\",\"erreur\": \"Le changement de nom n\'a pas pu être fait.\",\"debug\":\"{$requete}\"}";
				$json_string .= "}";
				die($json_string);
			}
		} else {
			$json_string = "{";
					$json_string .= "\"objet\":\"{$objet}\",";
					$json_string .= "\"action\":\"{$action}\",";
					$json_string .= "\"reponses\":";
						$json_string .= "{\"ID\": \"{$contenu['ID']}\",\"message\": \"erreur\",\"erreur\": \"Le nouveau nom ou l'identifiant du repère sont vides.\",\"debug\":\"{$requete}\"}";
				$json_string .= "}";
				die($json_string);
		}
		break;

}
?>