<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title> Ajout des membres </title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 </head>
 
 <body>

<?php

if (isset($_POST['Ajouter']) && $_POST['Ajouter'] == 'Ajouter') {
	//if (!empty($_POST['email'])&& !empty($_POST['password']) ){
		// Read POST variables	
		$email = $_POST['email'];
		$password = $_POST['password'];
		$Privilege = $_POST['Privilege'];	
			
	
		
		include ('connexion.php');
		//investigate whether this username is already used by another member
				$sql = 'SELECT * FROM compte WHERE email="'.mysql_real_escape_string($email).'"';
				$req = mysql_query($sql)or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());     
				$data = mysql_fetch_array($req); 
				
	
	

				
				if ($data[0] == 0) {
				
					// Query adeded user                 
					$sql = 'INSERT INTO compte VALUES("","'.$password.'","'.$email.'","'. $Privilege.'")';                              
					$query = mysql_query($sql);			
					if (!$query) {
						echo'ce membre n\'as pas pu etre ajouté';
						header("Location: ajout.php");
			}
					else{
						if (!session_start()) {
							echo'probleme session';
							header("Location: ajout.php");


						} 
						else {
							$_SESSION['email'] = $email;
							header("Location:adminhome.php");
						}
					}	
				}
				else{
					echo'ce membre n\'as pas pu etre ajouté';
					header("Location: ajout.php");
					
					}
				
			
				//Close connection
				mysql_close();	
			}
	elseif(isset($_POST['Annuler']) && $_POST['Annuler'] == 'Annuler'){
		header("Location: liste.php");
	}	

?>
 </body>
</html>
