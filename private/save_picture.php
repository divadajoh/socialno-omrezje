<?php require_once('includes/init.php'); ?>

<?php
	// ERROR CODES //
	// 1 - CSRF, 2 - Content missing, 3 - File error.



	if(isset($_POST['caption']) && isset($_POST['csrf_token']) && isset($_POST['picturePrivacyID']) ):
		$user_id = $_SESSION['user_id'];
		$caption = mysql_real_escape_string($_POST['caption']);
		$visibleTo = mysql_real_escape_string($_POST['picturePrivacyID']);
		$url = "";
		if(csrf_token_is_recent()):
			if(!($_POST['csrf_token'] == $_SESSION['csrf_token'])):
				header('Location: ../profile.php?profile_id='.$user_id.'&err_code=1');
				exit();
			endif;
		endif;

		// == HANDLE AND MOVE THE FILE == //
			if(basename($_FILES['fileUpload']['name']) != null):
			$ext = pathinfo($_FILES['fileUpload']['name'], PATHINFO_EXTENSION);
			$filename = uniqid() . '.'.$ext;
			$tmp_file = $tmp_file = $_FILES['fileUpload']['tmp_name'];
			$incomplete_url = 'users/'.$_SESSION['user_id'];
			$url = 'users/'.$_SESSION['user_id'].'/' . $filename;
			
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
		// == END OF FILE HANDLING == //


		// == DB STUFF == //
			global $database;
			$query  = "INSERT INTO pictures(ID, Uploader_ID, url, caption, visibleTo) ";
			$query .= "VALUES(0, '{user_id}', '{$url}', '{$caption}', '$visibleTo')";
			$result = $database->query($query);

			header('Location: ../profile.php?profile_id='.$user_id);

	endif;


exit();

?>