<?php
/**
* Fichier appelé par le WS CARTO (ws.carto.php)
* Projet LUDVIN 2013 - www.ludvin.org
* auteurs : J. Bodard, H. Boultache, F. Laazouia, B. Lathiere
* Université Paris 8 / Master "Technologie et Handicap"
*/


switch ($action) {

	case "verifier":

		$requete = "SELECT * FROM  `variables` WHERE  `name` = 'cartographe.lastversion'; " ;
		$resultat = $mysqli->query($requete);
		if ($resultat->num_rows>0) {
			$retour = $resultat->fetch_array(MYSQLI_ASSOC);
			$reponses .= "\"lastversion\": \"".$retour['value']."\"";
			$reponses .= ", \"message\":\"OK\"";
		} else {
			$reponses .= "\"message\":\"erreur\"";
			$reponses .= ", \"erreur\":\"Problème dans la base de données.\"";
			$reponses .= ", \"debug\":\"{$requete}\"";
		}
		break;
}
?>