<!--	//déjà présent dans entete.php !!

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title> liste des comptes </title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   </head>
 
   <body>
   -->
	
       <?php include("entete.php"); ?> <br/>
 
	
       <?php 
	   	   if ($_GET['msg']=="ok") {
			echo "<p class=msg>L'opération est réussie.</p>";
	   } elseif ($_GET['msg']=="erreur") {
			echo "<p class=msg>L'opération a échoué.</p>";
	   }
	   
	   include("userslist.php"); 
	   

	   
	   ?>
        
 
   </body>
</html>
