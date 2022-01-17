
<?php
	if (isset($_POST['supprimer'])) {
		if(!empty($_POST['delete'])){
	       	// Read POST variables	
		  	$Checkedbox=$_POST['delete'];
			
					//Connect to server
					include ('connexion.php');
					// Query
					$sql = "DELETE FROM compte WHERE ID IN (".implode(',', array_map('intval', $Checkedbox)).")";                             
					$query = mysql_query($sql);			
					if (!$query) {
					
					}
					else{		
						header("location: adminhome.php");			
					}					 
					//Close connexion
					mysql_close();
			
		}
		else {
		header("location: adminhome.php");
		}
	}
	
	
?>