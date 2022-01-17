<?php	session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>ics_updateMember</title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	</head> 
	<body>
	<?php
	function display_error() {
			return"<iguanodon><error>Error</error></iguanodon>";	
	}
	// Read POST variables
	if (empty($_POST['usernameEdit'])|| empty($_POST['Privileged'])|| empty($_SESSION['idMember']) ){
		display_error();
			exit;
		}	
		$idmember=$_SESSION['idMember'];
		$newusername=$_POST['usernameEdit'];
		$newpassword=$_POST['passwordEdit'];
		$newpasswordconfirm=$_POST['passwordconfirm'];
		$newPrivileged=$_POST['Privileged'];
	
	// XML output
		header("Content-type: text/xml");
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"; 		
		include('functions/fn_updateMember');
		fn_updateMember($idmember,$newusername,$newpassword,$newPrivileged)
	
	?>
	</body>
</html>