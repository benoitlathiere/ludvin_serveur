<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>fn_delete</title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> -->
	</head>
 
	<body>
		<?php
			function fn_deleteSelection($Checkedbox){
					// Connect to server
					include ('fn_connection.php');
					fn_iguanodonconnection();
					// Query
					$sql = "DELETE FROM members WHERE id IN (".implode(',', array_map('intval', $Checkedbox)).")";                             
					$query = mysql_query($sql);			
					if (!$query) {
						return"<iguanodon><delete>Error</delete></iguanodon>";	
						exit;
					}
					else{		
						return"<iguanodon><delete>Success</delete></iguanodon>";		
					}					 
					//Close connection
					mysql_close();
			}


			function fn_deletemember($idmember){
			
					// Connect to server
					include ('fn_connection.php');
					fn_iguanodonconnection();
					// Query
					$sql= "DELETE FROM members WHERE id=".$idmember;
					$query = mysql_query( $sql);
					if (!$query) {
						return"<iguanodon><delete>Error</delete></iguanodon>";	
						exit;
					}
					else{
						return"<iguanodon><delete>Success</delete></iguanodon>";
					}
					//Close connection
					mysql_close();
			}

		?>
	</body>
</html>

