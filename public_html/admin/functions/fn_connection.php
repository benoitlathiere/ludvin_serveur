<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>fn_ludvinconnection</title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> -->
	</head>
 
	<body>
		<?php
			function fn_ludvinconnection(){
			$projet_ludvin = mysql_connect("localhost", "root", "");
					if (!$projet_ludvin) {
					 header("Location: internalError.php");
						mysql_close();
						exit;
					}
					
				// Select database
				if (!mysql_select_db("projet_ludvin", $projet_ludvin)) {
					header("Location: internalError.php");
					mysql_close();
					exit;
				}
			}
		?>
	</body>
</html>
