



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title> Administrateur </title>
	</head>
	<script language="javascript" src="md5.js"></script>
	<script language="javascript">
					function encrypt() {
						
						document.authentication.password.value = MD5(document.authentication.passwordEdit.value);
					
						document.authentication.passwordEdit.value = "";
					}
					
					function editText(t) {
						if (t.value == t.defaultValue) {
							t.value = '';
							t.style.color = "#000000";
							if (document.selection && document.selection.clear)
								document.selection.clear();
						}
					}
					
					function inviteText(t) {
						if (t.value == '') {
							t.value = t.defaultValue;
							t.style.color = "#C0C0C0";
						} 
					}
					
					function editPassword(t) {
						if (t.value == t.defaultValue) {
							t.value = '';
							t.style.color = "#000000";
							if (document.selection && document.selection.clear)
								document.selection.clear();
							t.type = "password";
						}
					}
					
					function invitePassword(t) {
						if (t.value == '') {
							t.value = t.defaultValue;
							t.style.color = "#C0C0C0";
							t.type = "text";
						} 
					}
					
					function connectIn(t) {
						t.style.background = "#B4A139";
					}
					
					function connectOut(t) {
						t.style.background = "#E7D46C";
					}
		</script>
			<style>
			body {
				background-image: url(image/background.jpg);
				background-repeat: repeat;
				background-attachment: fixed;
			}
			.login_connection_title {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 20px;
				color: #FFF;
				text-shadow: gray 0.1em 0.1em 0.2em;
			}
			.input
			{
				font-size: 22px; 
				padding-left: 12px; 
				padding-right: 0px; 
				width: 234px; 
				height: 35px;
				color: #C0C0C0; 
				border: 0px; 
				border-radius: 5px;	
			}
			.submit
			{
				font-size: 22px; 
				width: 180px; 
				height: 37px; 
				color: #4F3400; 
				background-color: #E7D46C; 
				border: 0px; 
				border-radius: 5px; 
				text-shadow: gray 0.1em 0.1em 0.2em;
			}
			
			</style>
	<body>
		

			<p class="login_connection_title"> Connectez-vous avec votre identifiant </p>
			<form id="authentication" name="authentication" action="adminhome.php" method="post">
				<input class="input" id="usernameEdit" name="username" type="text" spellcheck="false" maxlength="128" value="Nom d'utilisateur" 
														onfocus="editText(this);" onblur="inviteText(this);"/>
														
				<input class="input" id="passwordEdit" name="passwordEdit" type="text" spellcheck="false" maxlength="128" value="Mot de passe" onfocus="editPassword(this);" onblur="invitePassword(this);"/>
				
				<input class="submit" type="submit" id="submit" value="Se connecter" onmouseover="connectIn(this);" onmouseout="connectOut(this);" onClick="encrypt(); return true;"/>
												
				<input type="hidden" name="password" value=""/>
			</form>
								
	</body>
</html>