<?php
/**
* Fichier appelé par le WS CARTO
* pour identification utilisateur
* Projet LUDVIN 2013 - www.ludvin.org
* auteurs : J. Bodard, H. Boultache, F. Laazouia, B. Lathiere
* Université Paris 8 / Master "Technologie et Handicap"
*/

	
switch ($action) {

	case "identifier":
	
		$requete = "SELECT ID FROM compte WHERE email='".($contenu["login"])."' AND password='".($contenu["pass"])."'  ;" ;
		$resultat = $mysqli->query($requete);
		$json_string .= "\"reponses\":";
		if ($resultat->num_rows>0) {
			$retour = $resultat->fetch_array(MYSQLI_ASSOC);
			$json_string  = "{\n";
			$json_string .= "\t\"reponse\": \"identification_acceptee\", \n";
			$reponses .= "\"reponse\": \"identification_acceptee\", ";
			$json_string .= "\t\"ID\": ".$retour['ID']."\n";
			$reponses .= "\"ID\": ".$retour['ID']."";
			$json_string .= "}";
			$reponses .= "}";
			$reponses .= ",\"message\":\"OK\"";
			$reponses .= ",\"debug\":\"{}\"";
			//die($json_string);
		} else {
			$json_string  = "{\n";
			$json_string .= "\t\"reponse\": \"identification_refusee\"\n";
			$reponses .= "\"reponse\": \"identification_refusee\"";
			$json_string .= "\n}";
			$reponses .= ",\"message\":\"erreur\"";
			$reponses .= ",\"erreur\":\"Identifiant ou mot de passe incorrect.\"";
			$reponses .= ",\"debug\":\"{$requete}\"";
			//die($json_string);		//login pas OK
		}
		break;

} 

?>