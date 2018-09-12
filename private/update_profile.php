<?php require_once('includes/init.php');?>

<?php
	// ERROR CODES //
	// 1 - CSRF, 2 - Content missing, 3 - File error.

	if(isset($_POST['csrf_token']) && isset($_POST['content']) && isset($_POST['privacySettingID'])):
		$user_id = $_SESSION['user_id'];
		$content = mysql_real_escape_string($_POST['content']);
		$visibleTo = mysql_real_escape_string($_POST['privacySettingID']);
/*
		if(csrf_token_is_recent()):
			if(!($_POST['csrf_token'] == $_SESSION['csrf_token'])):
				echo($_POST['csrf_token'] . '|' . $_SESSION['csrf_token']);
				//header('Location: ../profile.php?profile_id='.$user_id.'&err_code=1');
				exit();
			endif;
		endif;

*/
		
		if(strlen($content)>0):
			// == IF EVERYTHING IS SAFE == //

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
				header('Location: ../profile.php?profile_id='.$user_id.'&err_code=3');
				exit();
			}
		endif;
		// == END OF FILE HANDLING == //

		// == ENTERING DATA INTO THE DB == //
			// == CONTENT IS NOT ENTERED BECAUSE IT DOESNT MATTER == //
			if($file_appended):
			$photo_id = Photo::create($user_id, $url, "", $visibleTo);

			global $database;
			$query  = "INSERT INTO updates(ID, Creator_ID, Picture_ID, text)";
			$query .= "VALUES(0, '{$user_id}', '{$photo_id}', '{$content}')";
			$result = $database->query($query);

			else:
				global $database;
			$query  = "INSERT INTO updates(ID, Creator_ID, Picture_ID, text)";
			$query .= "VALUES(0, '{$user_id}', '0', '{$content}')";
			$result = $database->query($query);
			endif;

			header('Location: ../profile.php?profile_id='.$user_id);
		// == END OF ENTERING DB DATA == //

		else:
			header('Location: ../profile.php?profile_id='.$user_id.'&err_code=2');
			exit();
		endif;
	endif;

	exit();
?>