<?php 
//on r?p? les infos pass? en POST:
		
		if(isset($_POST) && !empty($_POST['email']) && !empty($_POST['password'])) {
		  extract($_POST);
		  $email= $_POST['email'];
		  $password= $_POST['password'];
		
		//connexion ?a base de donn?
			require ("connexion.php");
			 
				$sql = "SELECT * FROM compte WHERE email='".$email."' AND password='".$password."' AND privilege='Admin' ;" ;
				$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());

				if (@mysql_num_rows($req)>0) {
				// vers la page d'accueil de votre espace membres 
					session_start();
					$_SESSION['email'] = $email;
					 header('Location: adminhome.php');
					
					}
					
					
				  else {
					//echo"MVotre identifiant ou votre mot de passe n'a pas été reconnus. Merci de les vérifier et de réessayer.";			
					 header('Location: indexerror.php');
					
					}
}
		else {
				//echo"VVVVotre identifiant ou votre mot de passe n'a pas été reconnus. Merci de les vérifier et de réessayer.";			
				 header('Location: indexerror.php');	
				  exit; 
				} 			
?>