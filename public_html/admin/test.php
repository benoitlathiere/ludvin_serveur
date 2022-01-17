<?php
			
			if((!empty($_POST['email'])) && (!empty($_POST['password'])))
			{
				$email = $_POST['email'];
				$lol= $_POST['password'];

				echo $email ;
				echo $lol;
				}
				
				else { 
				echo'mince';
				}
?>