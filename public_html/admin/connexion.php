<?php
/*<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>ludvinconnection</title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	</head>
 
	<body>
		<?php
		*/
			
			//mysql_connect('localhost','ludvin','ludvin');
			$projet_ludvin = mysql_connect("localhost", "ludvin", "ludvin"); 
			if (!$projet_ludvin) {
				header("Location: internalError.php");
				mysql_close();
				}
					
			// Select database
			if (!mysql_select_db("projet_ludvin", $projet_ludvin)) {
				header("Location: internalError.php");
				mysql_close();
			
		}
			
		/*
	</body>
</html>
*/
?>