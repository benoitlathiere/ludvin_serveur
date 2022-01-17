<?php

function distance($bornes, $rbornes) {
  $dist = 0;
  foreach($bornes as $borne) {
    $dist += ($borne['signal'] - $rbornes[$borne['MAC']]) * ($borne['signal'] - $rbornes[$borne['MAC']]); 
  }
  return sqrt($dist);
}

// retourne la listes des reperes dont au moins une borne matche avec le scan $bornes
// exemple :
// [rep1] => [
//            MAC1 => signal
//            MAC2 => signal
//            MAC3 => signal
//           ]
// [rep2] => [
//            MAC2 => signal
//           ]
// [rep3] => [
//            MAC1 => signal
//            MAC4 => signal
//           ]
function get_reperes($bornes, $db) {
  $reperes = array();

  foreach ($bornes as $borne) {
    $req = "SELECT fk_repere, avg FROM mesuresbornesreperes WHERE mac='".$borne['MAC']."';";
    if (!($result = $db->query($req)))
      die("Invalid query: ".$db->error); 
    else {
      while ($row = $result->fetch_assoc()) {
	$reperes[$row['fk_repere']][$borne['MAC']] = $row['avg'];
      }
      $result->close();
    }
  }
  return $reperes;
}

function get_pois($bornes, $db) {
  $pois = array();
  $reperes = get_reperes($bornes, $db);
  $sorted_reperes = sorted_reperes($reperes, $bornes);
  foreach ($sorted_reperes as $repere => $distance) {
    $req = "SELECT nom, commentaire FROM repere WHERE ID='".$repere."' AND poi=1;";
    if (!($result = $db->query($req)))
      die("Invalid query: ".$db->error); 
    else {
      while ($row = $result->fetch_assoc()) {
	$pois[$repere]['nom'] = $row['nom'];
	$pois[$repere]['dist'] = $distance;
      }
      $result->close();
    }
  }
  return $pois;
}

// calcul la liste des reperes ordonnes par distance
// a la fin, on obtient un tableau $rep => $distance
function sorted_reperes($reperes, $bornes) {
  $sorted_reperes = array();
  
  foreach ($reperes as $repere => $rbornes) {
    $sorted_reperes[$repere] = distance($bornes, $rbornes);;
  }
  asort($sorted_reperes);
  return $sorted_reperes;
}

function print_pois($bornes, $db) {

  $pois = array();
  $pois = get_pois($bornes, $db);
  foreach ($pois as $poi => $val) {
      $req =  "SELECT R.nom, R.commentaire, E.nom AS etage_nom, B.nom AS batiment_nom FROM repere R JOIN etage E ON R.etage_ID = E.ID JOIN batiment B on E.batiment_ID = B.ID WHERE R.ID = '".$poi."';";
      if (!($result = $db->query($req)))
	die ("Invalid query: ".$db->error);
      else {
	$row = $result->fetch_assoc();
	if ($row['commentaire'])
	  echo "- ".$row['nom']." : ".$row['commentaire'].", ".$row['etage_nom'].", ".$row['batiment_nom']."\n";
	else
	  echo "- ".$row['nom'].", ".$row['etage_nom'].", ".$row['batiment_nom']."\n";
	$result->close();
      }
  }
}

function locate($bornes, $db) {
  // on recupere les reperes dont au moins une borne matche avec le scan $bornes
  $reperes = array();
  $reperes = get_reperes($bornes, $db); 

  // calcul des distances euclidiennes entre chaque repere et l'utilisateur
  $distances = array();
  foreach ($reperes as $repere => $rbornes) {
    $distances[$repere] = distance($bornes, $rbornes);
  }

  // on cherche le repere le plus proche
  $min = reset($distances); // rewinds array's pointer to the first element which is returned
  $rep_min = key($distances);
  foreach ($distances as $repere => $distance) {
    if ($distance < $min) {
      $rep_min = $repere;
    }
  }

  $req =  "SELECT R.nom, R.commentaire, E.nom AS etage_nom, B.nom AS batiment_nom FROM repere R JOIN etage E ON R.etage_ID = E.ID JOIN batiment B on E.batiment_ID = B.ID WHERE R.ID = '".$rep_min."' AND R.poi=0;";
  if (!($result = $db->query($req)))
    die("Invalid query: ".$db->error);
  else {
    $row = $result->fetch_assoc();
    if ($row['commentaire'])
      echo "Vous êtes en ".$row['nom']." : ".$row['commentaire'].", ".$row['etage_nom'].", ".$row['batiment_nom'];
    else
      echo "Vous êtes en ".$row['nom'].", ".$row['etage_nom'].", ".$row['batiment_nom'];
    $result->close();
  }
}

?>
