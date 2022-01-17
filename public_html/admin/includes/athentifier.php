<?php
//on rÈcupÈre les infos passÈes en POST:
if(isset($_POST) && !empty($_POST['email']) && !empty($_POST['password'])) {
  extract($_POST);
  $email= $_POST['email'];
  $password= $_POST['password'];
  
//connexion ‡ la base de donnÈes
	require ("connexion.php");
	
			
		$sql = "SELECT * FROM compte WHERE email='".$email."' AND password='".$password."' AND privilege='Admin' ;" ;
		$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());

		if (@mysql_num_rows($req)>0) {  
			session_start();
			$_SESSION['email'] = $email;
			
			echo 'Vous etes bien logu√©';
			echo $email;
		
			// vers la page d'accueil de votre espace membres 
		  }
		  else {
			
			echo ("CONNEXION_CARTOGRAPHE_REFUSEE");		//login pas OK
			}
		}
	else {
		  echo '<p>Vous avez oubli√© de remplir un champ.</p>';
		   include('..\index1.php'); // On inclut le formulaire d'identification
		   exit; 
		}

?>