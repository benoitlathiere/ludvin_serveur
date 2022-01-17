<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>fn_updateMember</title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> -->
	</head>
 
	<body>
		<?php
			function fn_updateMember($idmember,$newusername,$newpassword,$newPrivileged){
				// Connect to server
				include ('fn_connection.php');
				fn_iguanodonconnection();
				// Query
				$sql1 ="UPDATE members
						SET username ='".$newusername."', password='".md5($newpassword)."', Privileged='".$newPrivileged."'
						WHERE ID ='".$idmember."'";

				$sql2 ="UPDATE members SET  username ='".$newusername."', Privileged='".$newPrivileged."' WHERE ID ='".$idmember."'";

				//check password  
				$sql="";
					if ($newpassword==""){
						$sql= $sql2;
					}
					else{
						$sql= $sql1; 
					}
		 
				$query = mysql_query($sql);	 
				if (!$query) {
					$chaine= "<iguanodon>
								<userslist>
									<user>
										<id>Error</id>
										<username>Error</username>
										<password>Error</password>	
										<Privileged>Error</Privileged>
									</user>
								</userslist>	
							</iguanodon>";
							exit;
					
				}
				
				//Close connection
				mysql_close();
			}
			
			
			

			function fn_searchmemberWithId($idmember){
				$chaine="";
				include ('fn_connection.php');
				fn_iguanodonconnection();
			
				// Query user
				$sql = "SELECT * FROM members where id=".$idmember;				    
				$query = mysql_query($sql);			
				if (!$query) {
					$chaine= "<iguanodon>
								<userslist>
									<user>
										<id>Error</id>
										<username>Error</username>
										<password>Error</password>	
										<Privileged>Error</Privileged>
									</user>
								</userslist>	
							</iguanodon>";
							exit;
					
				}
				else{
					$chaine= "<iguanodon><userslist>";
					while ($row  = mysql_fetch_array($query)) { 			
						$chaine=$chaine." <user> <id>".$row['ID']."</id><username>".$row['username']."</username> <password>".$row['password']."</password><Privileged>".$row['Privileged']."</Privileged></user>";
					}	
					$chaine=$chaine."</userslist></iguanodon>";
				}	
				//release all memory and resources
				mysql_free_result($query);
			
				//Close database
				mysql_close();

				return $chaine;

			}
		?>
	</body>
</html>
