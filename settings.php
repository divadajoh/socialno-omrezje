<?php require_once('private/includes/init.php'); ?>
<?php if(!$session->is_logged_in()){header('Location: ../index.php?err_id=2');}?>
<?php include_once('private/layouts/user_header_layout.php'); ?>

<?php if(isset($_GET['settings_id'])): ?>
	<?php
		$firstButton = 'profileButton ';
		$secondButton = 'accountButton ';

		$settings_id = $_GET['settings_id'];
		
		$db_result = Picture::get_profile_picture_url($_SESSION['user_id']);
		if(isset($db_result['Profilna_Slika']) && $db_result['Profilna_Slika'] != ''):
			$profile_url = "/hojawebsite/private/users/" .$db_result['ID'] . '/' . $db_result['Profilna_Slika'];
		else:
			$profile_url = "";
		endif;

		if($_GET['settings_id'] == '1') {
			$firstButton .= 'selected';
			$secondButton .= 'unselected';
		}else if($_GET['settings_id'] == '2') {
			$secondButton .= 'selected';
			$firstButton .= 'unselected';
		}else{header('Location: home.php');}

		$user = User::find_by_id($_SESSION['user_id']);

		$name = htmlspecialchars($user->ime);
		$surname = htmlspecialchars($user->priimek);
		$email = htmlspecialchars($user->email);
		$location = htmlspecialchars($user->location);
		$phone_number = htmlspecialchars($user->phone_number);
		$interests = htmlspecialchars($user->interests);

	?>

<?php else: header('Location: settings.php?settings_id=1'); ?>

<?php endif;?>


<?php
	$err_message="";
 	if(isset($_GET['err_message']) && $_GET['settings_id'] == 1): 
 		$err_message = '*'. $_GET['err_message'];
 	else:
 		$user = User::find_by_id($_SESSION['user_id']);
 		if($user->first_timer == 0):
 			$err_message = "Before you proceed, please upload a profile picture.";
 		endif;
 	endif;

 	$error_message = "";
 	if(isset($_GET['err_message']) && $_GET['settings_id'] == 2): 
 		$error_message = '*'. $_GET['err_message'];
 		if(strpos($error_message, 'changed') == true):
 			$error_message = $_GET['err_message'];
 			$error_message = "<span style='color:green'>".$error_message."</span>";
 		endif;
 	else:
 		$user = User::find_by_id($_SESSION['user_id']);
 		if($user->first_timer == 0):
 			$error_message = "Before you proceed, please upload a profile picture.";
 		endif;
 	endif;


 	if($user->first_timer == 0){
			// == WRITE HIM A MESSAGES AS ME 'David' THANKING HIM FOR JOINING THE SITE == //
 			if(!Conversation::exists(1, $_SESSION['user_id'])){
 				Conversation::create_conversation(1, $_SESSION['user_id']);
				$conversation = Conversation::get_conversation_by_participators(1, $_SESSION['user_id']);
 				$message = "Hi, Thanks for joining the website!";
 				$message .=" This website is not meant to be a commercial website.";
 				$message .= " If you find a bug, please report it to me. Have Fun!";
 				Message::send_as_admin(1, $_SESSION['user_id'],$conversation->id, $message);
 				$messageSlo  = "Zdravo, Hvala da si ze prijavil na spletno stran!";
 				$messageSlo .="Ta spletna stran ni namenjena komercialni uporabi.";
 				$messageSlo .= "Ce na strani najdes hrosca mi to prosim sporoci. Uzivaj!";
 				Message::send_as_admin(1, $_SESSION['user_id'],$conversation->id, $messageSlo);
 			}
	}

 ?>



<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="private/scripts/settings.js"></script>
		<link rel="stylesheet" href="private/styles/settings.css">
	</head>

	<body>	
		<section>
			<div id="panel">
					<?php if($settings_id == 1): ?>
					<p class='errMessage'><?php echo(htmlspecialchars($err_message));?></p>

					<form action="private/save_settings.php" enctype="multipart/form-data" method="POST">
						
						<div class='profilePic' title="Profile picture">
								<?php
									$toEcho = "";
									if(!$profile_url == ''):
										$toEcho = "<img src='" . $profile_url ."'></img>" ;

									else:
										$toEcho = "<p>Upload picture</p>";
									endif;

									echo($toEcho);
								?>
						</div>

						<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
						<input type="file" name="fileUpload" accept="image/*" style="opacity:0">
						<input type="text" name="nameInput" maxlength="12" class="inputElement" placeholder="Name" title="Name" value="<?php echo($name);?>">
						<input type="text" name="surnameInput" maxlength="12" class ="inputElement" placeholder="Surname" title="Surname" value="<?php echo($surname);?>">
						<input type="text" name="locationInput" maxlength="15" class="inputElement" placeholder="Location" title="Where do you live?" value="<?php echo($location);?>">
						<input type="text" name="phoneNumber" maxlength="15" class="inputElement" placeholder="Phone number" title="Phone number" value="<?php echo($phone_number);?>">
						<input type="text" name="interests" maxlength="255" class="inputElement" placeholder="Interests" title="Interests/Hobbies" value="<?php echo($interests); ?>">
						<input type="hidden" name="formid" value="first">
						<?php echo(csrf_token_tag());?>
						<input type="submit" name="submitProfileForm" id= "sButton" value="Save">
					</form>

					<?php else: ?>
						<form action="private/save_settings.php" method="POST">
							<p class="errMessage"><?php echo($error_message);?></p>
							<input type="email" name="email" maxlength="50" class="inputElement" placeholder="Email" value="<?php echo($email); ?>">
							<input type="password" name="password" maxlength="15" class="inputElement" placeholder="New Password">
							<input type="password" name="cPassword" maxlength="15" class="inputElement" placeholder="Confirm new password">
							<input type="password" name="oldPassword" class="inputElement" placeholder="Enter old password for confirmation">
							<input type="hidden" name="formid" value="second">
							<?php echo(csrf_token_tag()); ?>
							<input type="submit" name="submitAccountForm" class="submitButton" value="Save">
						</form>

					<?php endif; ?>
			</div>

			<div class="<?php echo($firstButton);?>" onclick="location.href='settings.php?settings_id=1'">
				<p>Profile</p>
			</div>

			<div class="<?php echo($secondButton);?>" onclick="location.href='settings.php?settings_id=2'">
				<p>Account</p>
			</div>


		</section>
	</body>
	<?php include_once('private/layouts/user_footer_layout.php'); ?>

</html>
