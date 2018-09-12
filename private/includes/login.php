<?php
	require_once("init.php");

	if(isset($_POST['loginButton'])) {
		$email = trim($_POST['emailInput']);
		$password = trim($_POST['passwordInput']);
		$email = mysql_real_escape_string($email);
		$password = mysql_real_escape_string($password);

		$found_user = User::authenticate($email, $password);

		if($found_user) {
			$session->login($found_user);
			header('Location: ../../home.php');
		}else {
			//Invalid username or password.
		 	header('Location: ../../index.php?err_id=1');
		}
	}


?>