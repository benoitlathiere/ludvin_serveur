	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

	<html lang="fr">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

			<title> Administration des cartographes </title>

			<link rel="stylesheet" type="text/css" media="screen" href="css\index.css">						
			
			<script type="text/javascript" src="js\index.js" > </script>
			
		</head>
	
		<body>
		
		<div id="corp">
		
			<p> 
				Bonjour Administrateur ! <br>
			    <img Src="image/logo_carto.png" alt="cartographe"> <br>
				Connectez-vous avec votre email et mot de passe
			</p> <br>
			
			<form id="authentication" name="authentication" action="adminhome.php" method="post">
						<div><h6 id="label_inline"><label for="emailEdit" class="label_login"> Email:</label>
						<input class="input_mail" id="emailEdit" name="email" type="text" maxlength="128" value="Email" 
																onfocus="editText(this);" onblur="inviteText(this);"> </h6> </div>
																											
						<div><h6 class="label_inline"><label for="passwordEdit" class="label_password" > Mot de passe:</label>								
						<input class="input_password" id="passwordEdit" name="passwordEdit" type="text"  maxlength="128" value="password" onfocus="editPassword(this);" onblur="invitePassword(this);"> </h6></div>
						
						<div> <input class="submit" type="submit" id="submit" value="Se connecter" onmouseover="connectIn(this);" onmouseout="connectOut(this);" onClick="encrypt(); return true;">		
						
						<input class="hidden" type="hidden" name="password" value=""> </div>
			</form>
			
		</div>
		<p>
			<a href="http://validator.w3.org/check?uri=referer"><img
			  src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Strict" height="31" width="88"></a>
		</p>

		</body>
	</html>



