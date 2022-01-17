<?php
/**
* Fichier appelé par le client Android Cartographe pour enregistrement des mesures
* 
* Projet LUDVIN février-juin 2013
* auteurs : J. Bodard, H. Boultache, F. Laazouia, B. Lathiere
* Université Paris 8 / Master "Technologie et Handicap"
* http://handiman.univ-paris8.fr/~ludvin/
*/


/* FONCTIONNEMENT :
 * Le script reçoit un flux formaté en JSON,
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

//connexion Mysql procedural (OBSOLETE) :
/*
$res = mysql_connect($host, $user, $pass);
if (! $res)
	die("ERREUR:Problème connexion serveur!");
if (! mysql_select_db($base))
	die("ERREUR:Problème sélection base !");
mysql_set_charset("utf8");	//client Mysql en UTF-8, indispensable
*/

//connexion Mysql version objet	
$mysqli = new mysqli($host, $user, $pass, $base);
if ($mysqli->connect_error)
    die('Connect Error (' . $mysqli->connect_errno . ') '.$mysqli->connect_error);
$mysqli->set_charset("utf8");	//obligatoire pour nous



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


//source : http://www.codeproject.com/Articles/267023/Send-and-receive-json-between-android-and-php
$json = file_get_contents('php://input');
$obj = json_decode($json,true);	//tableau associatif
$bornes = $obj['bornes'];	//on récupère les mesures dans un tableau associatif


/*** ENREGISTREMENT DES POSITIONS ***/

//on récupère l'ID du cartographe
$req = "SELECT * FROM compte WHERE email='".$obj['user_login']."' AND password='".$obj['user_pass']."' LIMIT 1;";
$result=$mysqli->query($req);
$row = $result->fetch_array(MYSQLI_ASSOC);

//on boucle sur chaque mesure
foreach($bornes as $borne) {
	$req = "INSERT INTO mesures (mac, signal, frequence, date, mobile, borne, fk_repere, fk_compte) VALUES ('".$borne['MAC']."','".intval($borne['signal'])."', '".floatval($borne['frequence'])."', '".$borne['date']."', '".$obj['mobile']."', '".$borne['nom']."', '".intval($obj['repere'])."','".intval($row['ID'])."');";
	//echo $ireq."\n";	//debug
	//$req = $mysqli->real_escape_string($req);	//FIXME comportement bizarre !
	if (! $result = $mysqli->query($req) )
		echo "ERREUR : {$req}\n" ;
}
$mysqli->close();

if (json_last_error() != JSON_ERROR_NONE)
	die("ERREUR:Problème lors du décodage du flux JSON : ".json_last_error());

//TODO : faire réponse en JSON :
$json_string = "{\"reponses\":[\n";
$json_string .= "{\"reponse\":{\"message\":\"OK\"}}";
$json_string .= "]}";
die($json_string);





//construction du JSON pour la réposne au client
/*
$json_string = "{\"batiments\":[\n";
//$json_string .= "\t{\"batiment\":{\"ID\":\"{$row['ID']}\",\"nom\":\"{$row['nom']}\",\"etages\":\"{$row['nb_etages']}\"}},\n";   //JSON syntaxe
$json_string .= "]}";	//fin JSON
die($json_string);
        
die("ERREUR: Aucun bâtiment trouvé dans la base de données.");
*/





//source : http://stackoverflow.com/questions/4685534/android-json-to-php-server-and-back
/*
$data = file_get_contents('php://input');
//json_decode(stripslashes($_POST['vehicle']));
$json = json_decode($data);
//print $json;
$service = $json->{'service'};
print $service;
*/

?>
