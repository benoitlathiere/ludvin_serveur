<!--	//d�j� pr�sent dans entete.php !!

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
			echo "<p class=msg>L'op�ration est r�ussie.</p>";
	   } elseif ($_GET['msg']=="erreur") {
			echo "<p class=msg>L'op�ration a �chou�.</p>";
	   }
	   
	   include("userslist.php"); 
	   

	   
	   ?>
        
 
   </body>
</html>
