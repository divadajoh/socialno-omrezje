<?php require_once('../includes/init.php'); ?>

<?php
// == SERVER-SIDE DATA CHECKING == //

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		global $database;
		// == CHECK IF ITS A VALID EMAIL == //
		$user_email = $_POST['emailInput'];
        $user_email = mysql_real_escape_string($user_email);

		$is_valid_email = filter_var($user_email, FILTER_VALIDATE_EMAIL);
		if(!$is_valid_email){echo('628985'); exit();}

		$query_result = $database->query('SELECT * FROM uporabniki WHERE Email="'.$user_email.'" LIMIT 1');
		$num_rows = mysqli_num_rows($query_result);

		// == CHECKING THAT THE CLIENT ENTERED ALL FIELDS == //
		if(!($_POST['nameInput'] && $_POST['surnameInput'])){echo('485431');exit();}
		if(!($_POST['passwordInput'] && $_POST['confirmPasswordInput'])){echo('485431');exit();}
		if($num_rows == 1) {
			echo('852456');
			exit();
		}
		// == IF THE NAME AND SURNAME ARE LONGER THAN 12 chars each == //
		if(sizeof($_POST['nameInput']) + sizeof($_POST['surnameInput']) > 24){echo('error'); exit();}

		// == END OF EMAIL VALIDATION == //


		// == REGISTER USER == //

		$name = mysql_real_escape_string($_POST['nameInput']);
		$name = ucfirst($name);
		$surname = mysql_real_escape_string($_POST['surnameInput']);
		$surname = ucfirst($surname);
		$password = mysql_real_escape_string($_POST['passwordInput']);


		$query  = "INSERT INTO uporabniki(ID, Ime, Priimek, Email, Geslo) VALUES(";
		$query .= "0, '{$name}', '{$surname}', '{$user_email}', '{$password}')";
		$result_set = $database->query($query);



		// == REGISTRATION SUCCESFULL == //
		if($result_set){echo('011110');}
		


	}

?>