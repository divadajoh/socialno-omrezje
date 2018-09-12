<?php require_once('includes/init.php'); ?>

<?php
	// == || PROFILE FORM || == //
	$file_appended = false;
	$filename ="";
	if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['formid'] == 'first'):
		$err_message = "";
		if($_POST['nameInput']  == '' || $_POST['surnameInput'] == ''):
			$err_message = "Name and Surname fields cannot be empty";
			$err_message = urlencode($err_message);
			header('Location: ../settings.php?settings_id=1&err_message='.$err_message); exit();
		endif;


		if(csrf_token_is_recent()):
			if(!($_POST['csrf_token'] == $_SESSION['csrf_token'])):
				$err_message = "CSRF Authentication failed!";
				$err_message = urlencode($err_message);
				header('Location: ../settings.php?settings_id=1&err_message='.$err_message);
				exit();
			endif;
		endif;

		//Enter into DB.
		$name = mysql_real_escape_string($_POST['nameInput']);
		$surname = mysql_real_escape_string($_POST['surnameInput']);
		$location = mysql_real_escape_string($_POST['locationInput']);
		$phone_number = mysql_real_escape_string($_POST['phoneNumber']);
		$interests = mysql_real_escape_string($_POST['interests']);

		if((sizeof($name) + sizeof($surname)) > 26):
			header('Location: ../settings.php?settings_id=1>');
			exit();
		endif;

		if(basename($_FILES['fileUpload']['name']) != null):
			$ext = pathinfo($_FILES['fileUpload']['name'], PATHINFO_EXTENSION);
			$filename = uniqid() . '.'.$ext;
			$tmp_file = $tmp_file = $_FILES['fileUpload']['tmp_name'];
			$incomplete_url = 'users/'.$_SESSION['user_id'];
			$url = 'users/'.$_SESSION['user_id'].'/' . $filename;
			$user= User::find_by_id($_SESSION['user_id']);
			$old_file_name = $incomplete_url . '/'.$user->profile_picture;
			unlink($old_file_name);
			if(!is_dir($incomplete_url)):
				mkdir($incomplete_url);
			endif;


			if(move_uploaded_file($tmp_file, $url)) {
				$file_appended = true;
			}else {
				$error = $_FILES['file_upload']['error'];
				$message = $upload_errors[$error];
				header('Location: ../settings.php?settings_id=1&err_message='.$message);
			}
		endif;

		$upload_errors = array(

		UPLOAD_ERR_OK				=> "No errors",
		UPLOAD_ERR_INI_SIZE			=> "Larger than upload_max_filesize",
		UPLOAD_ERR_FORM_SIZE		=> "Larger then form MAX_FILE_SIZE",
		UPLOAD_ERR_PARTIAL			=> "Partial upload",
		UPLOAD_ERR_NO_FILE			=> "No file.",
		UPLOAD_ERR_NO_TMP_DIR		=> "No temporary directory.",
		UPLOAD_ERR_CANT_WRITE		=> "Can't write to disk.",
		UPLOAD_ERR_EXTENSION        => "Unknown extension."

		);

		global $database;
		if($file_appended):
		$query  = "UPDATE uporabniki ";
		$query .= "SET Ime='".$name.'\',';
		$query .= "Priimek='".$surname.'\',';
		$query .= "Location='".$location.'\',';
		$query .= "Profilna_Slika='".$filename.'\',';
		$query .= "Phone_Number='".$phone_number.'\',';
		$query .= "Interests='".$interests.'\',';
		$query .= "first_timer=".'1'." WHERE ID = ".$_SESSION['user_id']; 
		else:
			$query  = "UPDATE uporabniki ";
		$query .= "SET Ime='".$name.'\',';
		$query .= "Priimek='".$surname.'\',';
		$query .= "Location='".$location.'\',';
		$query .= "Phone_Number='".$phone_number.'\',';
		$query .= "Interests='".$interests.'\',';
		$query .= "first_timer=".'1'." WHERE ID = ".$_SESSION['user_id']; 
		endif;

		$db_result = $database->query($query);
		
		header('Location: ../settings.php?settings_id=1');

	endif;

	// || ACCOUNT FORM || //
	if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['formid'] == 'second'):
		$email = mysql_real_escape_string($_POST['email']);
		$password = mysql_real_escape_string($_POST['password']);
		$cPassword = mysql_real_escape_string($_POST['cPassword']);
		$oldPassword = mysql_real_escape_string($_POST['oldPassword']);
		$password = trim($password);
		$cPassword = trim($cPassword);
		$oldPassword = trim($oldPassword);

		$is_valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);
		if(!$is_valid_email):
			$err_message = "The email you entered is not valid.";
			header('Location: ../settings.php?settings_id=2&err_message='.$err_message);
			exit();
		endif;

		if($password != $cPassword):
			$err_message = "Your new password and confirmation password do not match.";
			$err_message = urlencode($err_message);
			header('Location: ../settings.php?settings_id=2&err_message='.$err_message);
			exit();
		endif;

		if(csrf_token_is_recent()):
			if(!($_POST['csrf_token'] == $_SESSION['csrf_token'])):
				$err_message = "CSRF Authentication failed!";
				$err_message = urlencode($err_message);
				header('Location: ../settings.php?settings_id=2&err_message='.$err_message);
				exit();
			endif;
		endif;

		//Checking password
		global $database;

		$query ="SELECT Geslo FROM uporabniki WHERE ID='".$_SESSION['user_id']."'";
		$db_result =$database->query($query);
		$result_array = mysqli_fetch_array($db_result);
		$retrieved_password = $result_array['Geslo'];
		
		if($retrieved_password != $oldPassword):
			$err_message = "Your old password is invalid.";
			$err_message = urlencode($err_message);
			header('Location: ../settings.php?settings_id=2&err_message='.$err_message);
			exit();
		endif;


		// || CHANGING DATABASE VALUES || //
		if($email != '' && $password == ''):
			$query ="UPDATE uporabniki SET Email='".$email."' WHERE ID='".$_SESSION['user_id']."'";
			$db_result = $database->query($query);
			if(!$db_result):
				$err_message = "There was an error on the Server.";
				$err_message = urlencode($err_message);
				header('Location: ../settings.php?settings_id=2&err_message='.$err_message);
			else:
				$err_message = "Email has been changed succesffuly.";
				$err_message = urlencode($err_message);
				header('Location: ../settings.php?settings_id=2&err_message='.$err_message);
			endif;
		endif;


		if($email != '' && $password != '' && $cPassword != ''):
			$query  ="UPDATE uporabniki SET Email='".$email."',";
			$query .="Geslo='".$password."' WHERE ID='".$_SESSION['user_id']."'";
			$db_result = $database->query($query);
			if(!$db_result):
				$err_message = "There was an error on the Server.";
				$err_message = urlencode($err_message);
				header('Location: ../settings.php?settings_id=2&err_message='.$err_message);
			else:
				$err_message = "Email and Password have been changed succesffuly.";
				$err_message = urlencode($err_message);
				header('Location: ../settings.php?settings_id=2&err_message='.$err_message);
			endif;
		endif;

	endif;
?>
