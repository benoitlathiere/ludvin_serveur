<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>fn_createmember</title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	</head>
 
	<body>
		<?php
			function fn_createmember ($username,$password,$Privileged) {
				
				// Connect to server
				include ('fn_connection.php');
				fn_ludvinconnection();
				//investigate whether this username is already used by another member
				$sql = 'SELECT * FROM compte WHERE username="'.mysql_real_escape_string($username).'"';
				$req = mysql_query($sql)or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());     
				$data = mysql_fetch_array($req); 
				
				if ($data[0] == 0) {
				
					// Query adeded user                 
					$sql = 'INSERT INTO members VALUES("","'.$username.'","'.md5($password).'","'. $Privileged.'")';                              
					$query = mysql_query($sql);			
					if (!$query) {
						return display_error();
						exit;
					}
					else{
						if (!session_start()) {
							return display_error();
						} 
						else {
							$_SESSION['ics_username'] = $username;
							return display_success();
						}
					}	
				}
				else{
					return display_error();
					exit;
				}
			
				//Close connection
				mysql_close();	
			}
		?>
	</body>
</html>
