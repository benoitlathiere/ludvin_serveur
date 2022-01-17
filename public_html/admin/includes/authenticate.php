<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Mon super site</title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 </head> 
<body>
<?php
			//if(!empty($_POST['email'])&& !empty($_POST['password'])) 
			if (isset ($_POST['email']))
			{
				// Read POST variables
				$email = $_POST['email'];
				echo 'bravo';
				$password = $_POST['password'];				
		
			// Connect to server
				include ('includes\connection.php');
				
				// Query
				$query = "SELECT * FROM compte WHERE email =\"$email\" AND password=\"$password\"";
				$result = mysql_query($query);
				if (!$result) {
					header("Location:loginerror.html");
					mysql_close();
					
				}
				if (mysql_num_rows($result) == 0) {
					header("Location:loginerror.html");
					mysql_close();
					exit;
				}
				//returns the result 
				$row = mysql_fetch_array($result);
					if (!empty($row))
					{
						if (!session_start()) {
						 header("Location:loginerror.html");
						} else { 				
						$_SESSION['email'] = $row['email'];//il faut transmettre cette variable dans le fichier adminhome.php 
						$_SESSION['privilege'] = $row['Privilege'];
						 if ( $_SESSION['privileged']=='Admin'){
							header("Location:adminhome.php");
						}
						elseif( $_SESSION['privileged']=='Carto'){
							header("Location:loginerror.html");
						}
						}
					}
					else {
					header("Location:loginerror.html");;
					
					}
				//release all memory and resources	
				mysql_free_result($result);
				
				// Close connection
				mysql_close();
				
			}
			
		
		else{
			header("Location:loginerror.html");
			}
?>
 </body>
</html>
