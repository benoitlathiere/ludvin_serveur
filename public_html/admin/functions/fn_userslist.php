<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>fn_userslist</title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> -->
	</head>	
	<body>
		<?php
			function fn_userslist() {				
				include ('fn_connection.php');
				fn_ludvinconnection();			
				//Query 
				$sql = "SELECT * FROM compte order by login";
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());            
				$query = mysql_query($sql);
					if (!$query) {
					return"echo'Error'";			
					}
				
				//returns the result 
				$res =  "<iguanodon><userslist>";
				while ($row  = mysql_fetch_array($query)) { 
				$res .= "<user>
							<username>".$row['login']."</username> 
							<id>".$row['ID']."</id>
						</user>";
				} 	
				$res .= "</userslist></iguanodon>";	
				//release all memory and resources	
				mysql_free_result($query);	
				 
				//Close connection
				mysql_close();
				return $res;
			}
		?>
	</body>
</html>


 
 
 
 
 