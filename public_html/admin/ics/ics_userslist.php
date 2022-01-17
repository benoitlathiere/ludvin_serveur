<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>ics_userlist</title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	</head> 
	<body>
	<?php
		function display_error() {
			return"<iguanodon><error>Error</error></iguanodon>";	
		}
		// XML output
		header("Content-type: text/xml");
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"; 
		include('fn_userslist.php');
		fn_userlist(); 
	
	?>
	</body>
</html>
	
 