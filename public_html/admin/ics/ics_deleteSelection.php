<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>ics_deleteSelection</title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	</head> 
	<body>
	<?php
	function display_error() {
			return"<iguanodon><error>Error</error></iguanodon>";	
	}
	// Read POST variables
	if (empty($_POST['delete'])) {
		display_error();
			exit;
		}	
	$Checkedbox=$_POST['delete'];	
	// XML output
		header("Content-type: text/xml");
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"; 		
		include('functions/fn_delete.php');
		fn_deleteSelection($Checkedbox);
	
	?>
	</body>
</html>
	