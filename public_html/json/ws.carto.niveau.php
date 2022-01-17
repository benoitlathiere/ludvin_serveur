<?php
/**
* Fichier appelé par le WS CARTO
* Projet LUDVIN février-juin 2013
* auteurs : J. Bodard, H. Boultache, F. Laazouia, B. Lathiere
* Université Paris 8 / Master "Technologie et Handicap"
*/


switch ($action) {


	/* LISTE DES NIVEAUX */
	case "lister":
		$requete = "SELECT e.*, count(r.ID) AS nb_reperes FROM etage e LEFT JOIN repere r ON e.ID=r.etage_ID  WHERE batiment_ID='".intval($contenu['batiment_ID'])."' GROUP BY e.ID ORDER BY e.nom ASC ;" ;
		$resultat = $mysqli->query($requete);
		if ($resultat===false) {
			$json_string  = "{";
				$json_string .= "\"objet\":\"{$objet}\",";
				$json_string .= "\"action\":\"{$action}\",";
				$json_string .= "\"reponses\":";
					$json_string .= "{\"ID\": 0,\"message\":\"erreur\",\"erreur\":\"Problème sur la liste des niveaux.\",\"debug\",\"{$requete}\"}";
					$reponses .= "\"ID\": 0,\"message\":\"erreur\",\"erreur\":\"Problème sur la liste des niveaux.\",\"debug\",\"{$requete}\"";
			$json_string .= "}";
			//die($json_string);
		} else {
			$json_string = "{";
				$json_string .= "\"objet\": \"{$objet}\",";
				$json_string .= "\"action\": \"{$action}\",";
				$json_string .= "\"reponses\":{";
				$reponses .= "\"nb\":".$resultat->num_rows.",";
				if ($resultat->num_rows>0) {
					$json_string .= "\"niveaux\":[";
					$reponses .= "\"niveaux\":[";
					while ($row = $resultat->fetch_array(MYSQLI_ASSOC)) {
						$json_string .= "{\"niveau\":{\"ID\": {$row['ID']},\"nom\":\"{$row['nom']}\",\"nb_reperes\": {$row['nb_reperes']},\"batiment_ID\": {$row['batiment_ID']}}},";	//JSON syntaxe
						$reponses .= "{\"niveau\":{\"ID\": {$row['ID']},\"nom\":\"{$row['nom']}\",\"nb_reperes\": {$row['nb_reperes']},\"batiment_ID\": {$row['batiment_ID']}}},";	//JSON syntaxe
					}
					$json_string = substr($json_string, 0, strlen($json_string)-1);        //PATCH : enlever virgule  du dernier élément
					$reponses = substr($reponses, 0, strlen($reponses)-1);        //PATCH : enlever virgule  du dernier élément
					$json_string .= "]";	//fermer niveaux
					$reponses .= "]";	//fermer niveaux
					$json_string .= ",\"message\":\"OK\"";
					$reponses .= ",\"message\":\"OK\"";
				} else {
					$json_string .= "\"message\":\"erreur\",\"erreur\":\"Aucun niveau à lister.\",\"debug\":\"{$requete}\"";
					//$reponses .= "\"message\":\"erreur\",\"erreur\":\"Aucun niveau à lister.\",\"debug\":\"{$requete}\"";
					$reponses .= "\"message\":\"OK\",\"debug\":\"Aucun repère à lister.\"";
				}
				
				$json_string .= "}";	//fermer reponses
			$json_string .= "}";
			//die($json_string);
		}
		break;

		

		
	/* NOUVEAU NIVEAU */
	case "creer":
		if ($contenu['nom']!="" && intval($contenu['batiment_ID'])!="") {
			$nom = $mysqli->real_escape_string($contenu['nom']);
			//on vérifie si le niveau existe déjà
			$requete = "SELECT * FROM etage WHERE nom='".$nom."' AND batiment_ID='".intval($contenu['batiment_ID'])."' ;" ;
			$resultat = $mysqli->query($requete);
			if ($resultat->num_rows>0) {
				$json_string  = "{";
					$json_string .= "\"objet\":\"{$objet}\",";
					$json_string .= "\"action\":\"{$action}\",";
					$json_string .= "\"reponses\":";
						$json_string .= "{\"ID\": 0,\"message\":\"erreur\",\"erreur\":\"Le  niveau {$nom} existe déjà.\"}";
				$json_string .= "}";
				die($json_string);
			}else{
				//on créé l'élément
				$requete = "INSERT INTO etage (nom, batiment_ID) VALUES ('".$nom."', '".intval($contenu['batiment_ID'])."') ; ";
				$resultat = $mysqli->query($requete);
				$id = $mysqli->insert_id;
				if ($id>0) {
					$json_string  = "{";
						$json_string .= "\"objet\":\"{$objet}\",";
						$json_string .= "\"action\":\"{$action}\",";
						$json_string .= "\"reponses\":";
							$json_string .= "{\"ID\": {$id},\"message\":\"OK\",\"info\":\"Le niveau a été créé.\"}";
					$json_string .= "}";
					die($json_string);
				}else{
					$json_string  = "{";
						$json_string .= "\"objet\":\"{$objet}\",";
						$json_string .= "\"action\":\"{$action}\",";
						$json_string .= "\"reponses\":";
							$json_string .= "{\"ID\": 0,\"message\":\"erreur\",\"erreur\":\"Le niveau n'a pu être créé.\",\"debug\": \"{$requete}\"}";
					$json_string .= "}";
					die($json_string);
				}
			}
		} else {
			$json_string  = "{";
				$json_string .= "\"objet\":\"{$objet}\",";
				$json_string .= "\"action\":\"{$action}\",";
				$json_string .= "\"reponses\":";
					$json_string .= "{\"ID\": 0,\"message\":\"erreur\",\"erreur\":\"Le nom du niveau ou le bâtiment est vide.\"}";
			$json_string .= "}";
			die($json_string);
		}
		break;

		
	/* SUPPRIMER NIVEAU */
	case "supprimer":
		if ($contenu['ID']!="") {
			//on supprime le niveau
			$requete = "DELETE FROM etage WHERE ID='".intval($contenu['ID'])."' LIMIT 1 ; ";
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
					$json_string .= '"reponses":';
						$json_string .= '{"ID": "","message": "erreur","erreur": "Problème lors de la suppression du niveau."}';
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
		if ($contenu['nom']!="" && $contenu['nouveau']!="" && $contenu['ID']!="") {
			$nom = $mysqli->real_escape_string($contenu['nouveau']);
			$requete = "UPDATE etage SET nom='".$nom."' WHERE ID='".intval($contenu['ID'])."' LIMIT 1 ; ";
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
						$json_string .= "{\"ID\": \"{$contenu['ID']}\",\"message\": \"erreur\",\"erreur\": \"Le changement de nom n\'a pas pu être fait.\"}";
				$json_string .= "}";
				die($json_string);
			}
		} else {
			die("ERREUR: Nom ou identifiant d'étage vide.");
		}
		break;



	/* là on ne sait pas ce que l'on fait..! */
	default:
		$input = file_get_contents('php://input');
		$jsonObj = json_decode($input, true);
		print_r("debug de PHP : ".$jsonObj);	//debug
		die("ERREUR: trou noir dans PHP ! Pas d'action trouvée !");	//debug

}
?>