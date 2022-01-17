<?php
/**
* Fichier appelé par le WS CARTO
* Projet LUDVIN - 2013 - www.ludvin.org
* auteurs : J. Bodard, H. Boultache, F. Laazouia, B. Lathiere
* Université Paris 8 / Master "Technologie et Handicap"
*/


switch ($action) {


	/* LISTE DES MESURES */
	case "lister":
		$requete = "SELECT * FROM mesuresreperejour WHERE idrepere='".intval($contenu['repere_ID'])."' ORDER BY jour DESC, nb DESC;" ;
		$resultat = $mysqli->query($requete);
		if ($resultat===false) {
					$reponses .= "\"ID\": 0,\"message\":\"erreur\",\"erreur\":\"Problème sur la liste des mesures.\",\"debug\":\"{$requete}\"";
		} else {
				$reponses .= "\"nb\":".$resultat->num_rows.",";
				//if ($resultat->num_rows>0) {
					$reponses .= "\"mesures\":[ ";
					while ($row = $resultat->fetch_array(MYSQLI_ASSOC)) {
						$reponses .= "{\"mesure\":{" ;
							$reponses .= "\"email\":\"{$row['email']}\"," ;
							$reponses .= "\"nom\":\"{$row['nom']}\"," ;
							$reponses .= "\"idcompte\": {$row['idcompte']},";
							$reponses .= "\"nb\": {$row['nb']},";
							$reponses .= "\"jour\":\"{$row['jour']}\",";
							$reponses .= "\"idrepere\": {$row['idrepere']}";
						$reponses .= "}},";
					}
					$reponses = substr($reponses, 0, strlen($reponses)-1);        //PATCH : enlever virgule  du dernier élément
					$reponses .= "]";	//fermer mesures
					$reponses .= ",\"message\":\"OK\",\"debug\":\"{$requete}\"";
				/*} else {
					$reponses .= "\"message\":\"OK\",\"debug\":\"Aucune mesure à lister.\"";
				}*/
		}
		break;

		
	/* CREER MESURES */
	case "creer":
		$bornes = $contenu['bornes'];
		//on boucle sur chaque mesure
		$good=true;
		$msg="";
		foreach($bornes as $borne) {
			$req = "INSERT INTO mesures (`mac`, `signal`, `frequence`, `date`, `mobile`, `borne`, `fk_repere`, `fk_compte`) VALUES ('".$borne['MAC']."','".intval($borne['signal'])."', '".floatval($borne['frequence'])."', '".$borne['date']."', '".$obj['mobile']."', '".$borne['nom']."', '".intval($contenu['repere_ID'])."','".intval($user_id)."');";
			if (! $result = $mysqli->query($req) ) {
				$good=false;
				$msg .= "ERREUR : {$req}\n" ;
			}
		}
		$json_string = "{";
			$json_string .= "\"objet\":\"{$objet}\",";
			$json_string .= "\"action\":\"{$action}\",";
			$json_string .= "\"reponses\":";
				$json_string .= "{\"ID\": 0,\"message\":\"OK\",\"debug\":\"{$msg}\"}";
				$reponses .= "\"ID\": 0,\"message\":\"OK\",\"debug\":\"{$msg}\"";
		$json_string .= "}";	//fermer reponses
		//die($json_string);
		break;
	

		
	/* SUPPRIMER MESURES */
	case "supprimer":
		if ($contenu['jour']!="" && intval($contenu['repere_ID'] !=0) ) {
			//on supprime
			$requete = "DELETE FROM mesures WHERE fk_repere='".intval($contenu['repere_ID'])."' AND fk_compte='".$user_id."' AND SUBSTRING(date FROM 1 FOR 10)='".$contenu['jour']."' ; ";
			$resultat = $mysqli->query($requete);
			if ($resultat) {
				$json_string = "{";
					$json_string .= "\"objet\":\"{$objet}\",";
					$json_string .= "\"action\":\"{$action}\",";
					$json_string .= "\"reponses\":";
						$json_string .= "{\"ID\": ".$contenu['ID'].",\"message\":\"OK\"}";
				$json_string .= "}";
				$reponses .= "\"message\":\"OK\"";
				//die($json_string);
			} else {
				$json_string = '{';
					$json_string .= "\"objet\":\"{$objet}\",";
					$json_string .= "\"action\":\"{$action}\",";
					$json_string .= "\"reponses\":";
						$json_string .= "{\"ID\": 0,\"message\": \"erreur\",\"erreur\": \"Problème lors de la suppression des mesures.\",\"debug\":\"{$requete}\"}";
				$json_string .= "}";
				//die($json_string);
				$reponses .= "\"message\": \"erreur\", \"erreur\": \"Problème lors de la suppression des mesures.\", \"debug\":\"{$requete}\"";
			}
		} else {
			$json_string = '{';
				$json_string .= "\"objet\":\"{$objet}\",";
				$json_string .= "\"action\":\"{$action}\",";
				$json_string .= "\"reponses\":";
					$json_string .= "{\"ID\": 0,\"message\": \"erreur\",\"erreur\": \"L'un des éléments (jour, repère) est manquant.\"}";
			$json_string .= "}";
			//die($json_string);
			$reponses .= "\"message\": \"erreur\",\"erreur\": \"L'un des éléments (jour, repère) est manquant.\"";
		}
		break;

}
?>