<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>liste des utilisateurs</title>
	    <script language="javascript">
			function cocherTout(etat){
				var cases = document.getElementsByTagName('input');   // on recupere tous les INPUT
				for(var i=0; i<cases.length; i++)     // on les parcourt
				if(cases[i].type == 'checkbox')     // si on a une checkbox...
				{cases[i].checked = etat;}
				
			}
			function deleteperson( identifiant )
				{
					var confirmation = 	window.confirm( "Voulez vous vraiment supprimer cet enregistrement ?" ) ;		
					if( confirmation )
					{
					window.location.href = "deleteOnemember.php?idPersonne="+identifiant ;
					}
				}
			function updateperson( identifiant ){
			
				window.location.href = "searchOneMember.php?idPersonne="+identifiant ;				
			
			}


    
			function colorier(quoi){  
				if ( quoi.checked ){
					quoi.parentNode.parentNode.style.backgroundColor="#6ac1d4";
				}
				else { 
					quoi.parentNode.parentNode.style.backgroundColor="transparent";
				}
			}
	
		</script>    
		<style type="text/css">
			#tab
				{
					border-collapse: separate;
					border-spacing: 3px 3px;
					margin-top:150px ; 
				}
			#tab caption
				{
					background-color: #B8C7D3;
				}
			table
				{	width:600px;
					margin:auto;
					margin-top:100px ; 
					margin-bottom: 300px ; 
					margin-left: 300px ;
				}

			.submit 
				{
					border:none;
			 
					background:url('image/supprimer.png')no-repeat ;
			 
				}
			col 
				{
					background-color:#B8C7D3;
			 
				}
				th , .lastline
				{
					background-color:#669999;
			 
				}
		</style>
	</head>

	<body>
		<form method="post" action="deleteSelection.php" >
	<table id="tab">
					<caption> Affichage de la liste des membres </caption>
			<colgroup>
				<col span="1" width="40" />
				<col span="1" width="40"/>
				<col span="1" width="40" />
				<col span="1" width="120" />
				<col span="1" width="80" />
			</colgroup>
		<tr>
		<th colspan="3">option</th>
		<th> Email </th>
		<th> Privilège </th>
		</tr> 

		<?php
			include ('connexion.php');
						
				//Query 
				$sql = "SELECT * FROM compte order by email";
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());            
				$query = mysql_query($sql);
					if (!$query) {
					echo'Error';			
					}
					else {
					//returns the result 
				
				while ($row= mysql_fetch_array($query)) { 
				
					//echo $row['ID']." ".$row['email']." ".$row['password']." ".$row['privilege']."<br/>" ;  
					
				 		
				echo  '<tr onmouseover="this.bgColor=\'#B0E0E6\';" onmouseout="this.bgColor=\'#B8C7D3\';">
			
						<td align="center"><input type="checkbox" name="delete[]"  value="'.$row['ID'].'" id="checkboxedit"  onchange="colorier(this);" /></td>
						<td align="center"><img src="image/edit-un-stylo-ecrire-icone-9494-16.png" alt="modifier" title="modifier"  onclick="updateperson(\''.$row['ID'].'\');";/> </td>
						<td align="center"> <img border=0 src="image/supprimer.png" title="supprimer"  onclick="deleteperson(\''.$row['ID'].'\');"> </td>
						<td align="center">'. $row['email'].'
						
						<td align="center">'. $row['privilege'].'

						</td>			
					
					</tr>'; 
						
			}
				//release all memory and resources	
				mysql_free_result($query);	
				
				//Close connection
				mysql_close(); 
				
								}
				
			
		?>
		<tr>
			  <td class="lastline" colspan="5"><label for=""> tout cocher/tout décocher </label><input type="checkbox" name="choix"  onclick="cocherTout(this.checked);" />
			  <label for=""> pour la sélection :</label>  <input type="submit" name="supprimer" value="" title="tout supprimer" class="submit" onclick=" deleteperson();" /></td>
		</tr>

		</table>
		</form>

</body>

</html>
