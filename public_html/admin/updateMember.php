<?php
	session_start();
	if(!empty($_POST['email'])  && !empty($_POST['Privilege'])&& !empty($_SESSION['idMember'])){
		$idmember=$_SESSION['idMember'];
		$newemail=$_POST['email'];
		$newpassword=$_POST['password'];
		$newpasswordconfirm=$_POST['passwordconfirm'];
		$newPrivilege =$_POST['Privilege'];
		
		if ($newpassword==$newpasswordconfirm){
		// Connect to server
				require ('connexion.php');
				// Query
				$sql1 ="UPDATE compte
						SET email ='".$newemail."', password='".$newpassword."', privilege='".$newPrivilege."'
						WHERE ID ='".$idmember."'";

				//$sql2 ="UPDATE compte SET  email ='".$newemail."', privilege='".$newPrivilege."' WHERE ID ='".$idmember."'";
echo $sql1;
				//check password  
				/*
				$sql="";
					if ($newpassword==""){
						$sql= $sql2;
					}
					else{
						$sql= $sql1; 
					}
				*/
				$query = mysql_query($sql1);	 

				if (!$query) {
					header("Location: liste.php?msg=erreur");
					die();
				}
				
				//Close connection
				mysql_close();
			}
		header("Location: liste.php?msg=ok");
	} else {
		header("Location: liste.php?msg=erreur");
	}
	die();
?>