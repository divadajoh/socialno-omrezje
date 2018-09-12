<?php require_once('private/includes/init.php'); ?>

<?php if(!$session->is_logged_in()){header('Location: index.php?err_id=2');}?>


<?php // Checking that the user is not a first timer.
	$self = $_SERVER['PHP_SELF'];
	if(strpos($self,'settings.php') === false):
		$user = User::find_by_id($_SESSION['user_id']);
		if($user->first_timer == 0): // IF he didn't setup his profile yet.
			$err_message = "Before you proceed, please upload a profile picture.";
			$err_message = urlencode($err_message);
			header('Location: settings.php?settings_id=1&err_message='.$err_message);
			exit();
		endif;
	endif;

?>

<?php
	$user = User::find_by_id($_SESSION['user_id']);
	$full_name = htmlspecialchars($user->ime . ' ' . $user->priimek);
	$last_conversation_id = Conversation::get_last_chat_partner_id($_SESSION['user_id']);
	$last_conversation_id = "messages.php?recipient_id=".$last_conversation_id;

	// == GETTING THE PROFILE PICTURE == //
	$db_result = Picture::get_profile_picture_url($_SESSION['user_id']);
	$profile_url = "/hojawebsite/private/users/" .$db_result['ID'] . '/' . $db_result['Profilna_Slika'];

?>

<!DOCTYPE HTML>
<html>	
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="private/scripts/user_header_script.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

		<link rel="stylesheet" href="private/styles/user_header_style.css">
	</head>

	<body>
		<header>
			<?php if($user->first_timer == 1): ?>
			<input type="text" id="searchInput" placeholder="Find people that you may know." onclick="textVanish()">
			<span id="searchIcon"><i class='fa fa-search'></i></span>
			<img id="profile_pic" src="<?php echo($profile_url); ?>"></img>
			<p id="uhfName" onclick="location.href='profile.php?profile_id=<?php echo($_SESSION['user_id']); ?>' "><?php echo($full_name); ?></p>
			<div id="notificationButton" title="Notifications"><img src="public_images/notification_icon.png"></img></div>
			<div id="messageButton" title="Messages" onclick="location.href='<?php echo($last_conversation_id); ?>'"><img src="public_images/mesage_icon.png"></img></div>
			<div id="settingsButton" title="Settings" onclick="location.href='settings.php?settings_id=1'"><img src="public_images/settings_icon.png"></img></div>
			<?php endif; ?>
			<div id="logoutButton" onclick="location.href='private/includes/logout.php'"><span>Logout</span> </div>

		</header>

			<div id="searchResults">
			</div>

		<script>
			function textVanish() {
				document.getElementsByTagName('input')[0].value="";
			}
		</script>
	</body>

</html>


