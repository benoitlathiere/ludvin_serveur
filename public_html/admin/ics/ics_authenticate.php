<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>ics_authenticate</title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	</head> 
 <body>
<?php
	function display_error() {
			return"<iguanodon><error>Error</error></iguanodon>";
	
	}
// Read POST variables
	$username = $_POST["username"];
	if (empty($username)) {
	display_error();
		exit;
	}
	$password = $_POST["password"];
	if (empty($password)) {
	display_error();
		exit;
	}
	// XML output
	header("Content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
	include('functions/fn_authenticate.php');
	 fn_authenticate($username,$password);
	
	
?>
 </body>
</html>
