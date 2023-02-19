<?php
if ($_GET['uniquetoken'] == '4dc2a823-179e-463c-9fb1-6f221d9d90e4') 
		{

		$email = $_GET['email'];
				
		$servername = "localhost";
		$username = "becactussf";
		$password = "LAM8JFz8#!";
		$dbname = "panel_becactus";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		 // die("Connection failed: " . $conn->connect_error);
		}

		// on récupère les infos du client via l'adresse email 
		$req  = "SELECT CustomerPhoneNumber,Gender,CustomerLastName,CustomerFirstName,Renewal_SMS FROM customers WHERE CustomerEmail = '$email'";
		$result = $conn->query($req);
		while($row = $result->fetch_assoc()) {
				
				$CustomerPhoneNumber = $row["CustomerPhoneNumber"];
				$CustomerName = $row["CustomerLastName"] . " " . $row["CustomerFirstName"];
				
				if ($row["Renewal_SMS"] == 'false'){ // si on indique que le client ne veux pàas recevoir de sms on arrete le script
					exit();
					
				}
				
				if ($row["Gender"] == "Madame") { 
						$Gender = "Chère cliente";
				}
				else
					{
						$Gender = "Cher client";
					}
				
				}
		

			
		

		$message = $Gender . ', un avis de renouvellement de votre abonnement internet Be Cactus contenant un lien de paiement vous a été envoyé par email à l\'adresse '.$email.'. Vous pouvez également opter pour le paiement automatique, contactez-nous. Merci. L\'équipe Be Cactus. 0800/370.40';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.smsfactor.com/send?text='.$message.'&to='. $CustomerPhoneNumber .'&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxMDE0OSIsImlhdCI6MTY3MjQ5NjMwNy4zOTUxOTh9.FxFaVv75-6xePEC9vuiupsyZVacqsPvKtfO5FqKzyYU');
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch); 

		
		
		
				
		
			
		// on log l'envoi du sms dans la table sms_logs
		$sql = "INSERT INTO `sms_logs` (`ID`, `Datetime`, `Message`, `Number`, `CustomerName` ) VALUES (NULL,  current_timestamp() , '".addslashes(utf8_decode($message))."', '$CustomerPhoneNumber', '$CustomerName') ";
		
	

		if ($conn->query($sql) === TRUE) {
		    //  echo "New record created successfully SMS table" ; // decommenter ici a des fins de debug
				
			
			
		
		
			} else {
			 // echo "Error SMS table: " . $sql . "<br>" . $conn->error;
			}		
			
			

		}


else
			echo "INCOMPLETE REQUEST";
	exit;
?> 

