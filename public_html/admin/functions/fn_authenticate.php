<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>fn_authenticate</title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> -->
	</head>
 
	<body>
		<?php
			function fn_authenticate ($login,$password) {
				
				// Connect to server
				include ('fn_connection.php');
				fn_ludvinconnection();
				// Query
				$query = "SELECT * FROM compte WHERE login =\"$login\" AND password=\"$password\"";
				$result = mysql_query($query);
				if (!$result) {
					return display_error();
					mysql_close();
					exit;
				}
				if (mysql_num_rows($result) == 0) {
					return display_error();
					mysql_close();
					exit;
				}
				//returns the result 
				$row = mysql_fetch_array($result);
					if (!empty($row))
					{
						if (!session_start()) {
						 return display_error();
						} else { 				
						$_SESSION['login'] = $row['login'];//il faut transmettre cette variable dans le fichier adminhome.php 
						$_SESSION['privilege'] = $row['Privilege'];
						 return display_success();
						}
					}
					else {
					return display_error();
					exit;
					}
				//release all memory and resources	
				mysql_free_result($result);
				
				// Close connection
				mysql_close();
				
			}
		?>
	</body>
</html>
