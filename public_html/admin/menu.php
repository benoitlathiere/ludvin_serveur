<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>  </title>
<style type="text/css">
          
			body
					{
						background-image:url("image/background.jpg"); 
						background-attachment:fixed;

					}
		   .identifiant
					{ 
						border-radius : 10px;
						border-width:thin;
						font-size: 22px; 
						padding-left: 12px;
						padding-right: 0px; 
						width: 234px;
			  
					}
			.important
					{ 
						cursor: pointer; 
						font-weight: bold;
						background-color: #bbb; 
						padding: 4px 14px 6px; 
						border-radius: 5px; 
						inset 0 1px 4px #666, 
						inset 0 -6px 6px #666; 
						background-color: #4681b3;
						overflow:hidden;
						-moz-border-radius:10px;
						-webkit-border-radius:10px;
						border-radius:10px;
						border: 1px solid #103b5c;
						color:#fff
					}

			input[type=submit]:hover 
					{
						background-color:#0a60a2;
					}
						#contenu 
					{

						margin-top: 170px ; 
						margin-bottom: 5px ; 
						margin-left: 400px ; 
						font-weight: bold; 
						color:#83a1ae;

    
					} 

						#transparence 
					{
						width:700px;
						height:350px;
						margin-top:120px ; 
						margin-bottom: 300px ; 
						margin-left: 300px ; 
						font-weight: bold; 
						color:#83a1ae;
						border:4px solid #0c3555;
						overflow:hidden;
						-moz-border-radius:10px;
						-webkit-border-radius:10px;
						border-radius:10px;
						background-color:#000;
						filter:alpha(opacity=12);
						opacity:0.3;
						position:absolute;
						z-index:-1;
					}
			a:link
					{
						text-decoration:none;
					}

    </style>
	   
	<script language="javascript">
	function encrypt() {
		
		document.authentication.password.value = MD5(document.authentication.passwordEdit.value);
		document.authentication.passwordEdit.value = "";
		
	}
	function validateForm(f){
		if (f.passwordEdit.value != f.passwordconfirm.value) {
		alert('Ce ne sont pas les mêmes mots de passe!');
		f.passwordEdit.focus();
		return false;
		}
		else if (f.passwordEdit.value == f.passwordconfirm.value) {
		return true;
		}
	}

	</script>




</head>




<body>
 <div id="transparence">  </div>
  
 <div id="contenu">

	<form method="post" action="createmember.php" onsubmit="return validateForm(this);">

			<h1>Enregistrement d'un membre </h1>
			<table>
					<tr>
						<td>Email de l'utilisateur</td>
						<td>
						<input class="identifiant"  id="usernameEdit" name="email" type="text" spellcheck="false" maxlength="128" />
						</td>
					</tr>
				
						<td> Mot de passe </td>
						<td>
						<input class="identifiant"  id="passwordEdit" name="password" type="password" spellcheck="false" maxlength="128"/>
						</td>						
						
				
					</tr>
					<tr>
						<td>Confirmation du mot de passe</td>
						<td>
						<input class="identifiant"  id="passwordconfirm" name="passwordconfirm" type="password" spellcheck="false" maxlength="128"/>
						</td>
				
					</tr>
			
			  
					</tr>
			
					<tr>
					</tr>	
					<tr>
					</tr>	
					<tr>
					</tr>	
					<tr>
					</tr>	
			    
                    <tr>
                         <td> Vous ajoutez un membre:</td>
		            </tr>
					<tr>
					</tr>
		            <tr>
					<td><input type="radio" name="Privilege" value="Carto" checked="checked" id=""/> <label for=""> Cartographe</label></td>
		            </tr>
		 
		            <tr>
					</tr>
		            <tr>
		                 <td><input type="radio" name="Privilege" value="Admin" id=""/> <label for=""> Administrateur </label></td>
		            </tr>
					<tr>
					</tr>	
			   
					<tr>
					</tr>	
					<tr>
					
			                                    
						<td>
						</td>  
						<td colspan=2>
				            <input class="important" type="submit" name="Ajouter" value="Ajouter" />
				            <input class="important" type="submit" name="Annuler" value="Annuler"/>   
				        </td>
  
                            
					</tr>	
			                                   
			
			</table>

	
		</form>
	</div>
	

</body>
</html>