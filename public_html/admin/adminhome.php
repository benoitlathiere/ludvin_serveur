<?php
session_start();
?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title> accueil administrateur </title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <style type="text/css">
		   body { 

					background-image : url("image/background.jpg"); 

					background-attachment : fixed; 
					} 
					#nav li { 

					display : inline; 
					} 
					#nav a:link, #nav a:visited {

					padding : 20px 40px 4px 10px; 

					float : left; 

					width : auto; 

					font : bold 1em/1em Arial, Helvetica, sans-serif; 
				} 



					#nav a:hover { 

					color : #fff; 

					background : #10222f; 
					} 

					 #nav-home a:hover,  #nav-about a:hover, 
					{
					  background:#173144;
					  
					  text-shadow:none;
					  
					}

					#nav-home a:hover, #nav-about a:hover { 

					background : #173144; 

					 
					} 
					 #nav a:active 
					{
					   background:#0f212e;
					   
					}                           
					 a img { 

					border : none; 
					} 
					.text { 

					color : #83a1ae; 

					text-align : center; 
					} 
					#contenu { 

					margin-top : 170px; 

					margin-right : 20%; 

					margin-bottom : 5px; 

					margin-left : 190px; 
					} 
					a:link { 

					text-decoration : none; 
					color:#83a1ae; 

					} 
					.chopin {
						font-family:verdana, sans-serif;
						font-size:1em;
						color:#83a1ae; 
					}
 
	   </style>
   </head>
 
   <body>
	
		
		
	
       <?php include("entete.php"); ?> <br/>
	   
	   <h1 class="chopin"> Bienvenue <?php echo $_SESSION ['email']; ?> </h1> 	
 
       <?php include("userslist.php"); ?>
        
 
   </body>
</html>