<?php

	if(!empty($_GET['idPersonne'])){
	
		$idmember=$_GET['idPersonne'];
					
					// Connect to server
					include ('connexion.php');
					// Query
					$sql= "DELETE FROM compte WHERE ID =".$idmember;
					$query = mysql_query( $sql);
					if (!$query) {
						echo'requete?';
					}
					else{
						header("location:adminhome.php");
					}
					//Close connexion
					mysql_close();

	
		}
	
?>