<?php require_once('private/includes/init.php'); ?>
<?php include_once('private/layouts/user_header_layout.php'); ?>

<?php
	$user_id = 0;
	if(isset($_GET['profile_id'])): 
		$user_id = htmlspecialchars($_GET['profile_id']); 
	endif;

	// == THE USER THAT THE PROFILE BELONGS TO == // 
	$profile_owner = User::find_by_id($user_id);
	$full_name = htmlspecialchars($profile_owner->ime . ' ' . $profile_owner->priimek);

	// == THE USER THAT IS VIEWING THE PROFILE == //
	$profile_visitor =User::find_by_id($_SESSION['user_id']);

	// == CHECKS IF THE OWNER AND VIEWER ARE FRIENDS == //
	$is_friend = Friendship::check_friendship($user_id, $profile_visitor->id);

	// == CHECKS IF THE PERSON VIWEING THE PROFILE IS THE OWNER OF THE PROFILE == //
	$is_owner = ($profile_owner->id == $profile_visitor->id) ? true : false;


	// == GETS THE PROFILE PICTURE OF THE PROFILE OWNER == //
	$db_result = Picture::get_profile_picture_url($user_id);
	$profile_url = "/hojawebsite/private/users/" .$db_result['ID'] . '/' . $db_result['Profilna_Slika'];

	// == CHECKS IF THE VIWEING USER ALREADY SENT A FRIEND REQUEST == //
	$request_sent= Friendship::friend_request_exists($profile_visitor->id, $profile_owner->id);
?>

<html>
	<head>
		<link rel="stylesheet" href="private/styles/upper_profile_style.css">;
</head>



<section id="wrapper">

<section id="upperSection">
	<div class="profilePic"><img src="<?php echo($profile_url); ?>"></img></div>
	<div id="nameContainer"><p><?php echo($full_name); ?></p></div>
	<?php if(!$is_friend && !$is_owner && !$request_sent): ?>
		<div class="friendshipButton notFriend">
			<p>Send friend request</p>
		</div> 

	<?php elseif($request_sent): ?>

		<div class="friendshipButton notFriend requestSent">
			<p style="color:gray">Friend request sent</p>
		</div> 

	<?php endif; ?>


</section>


<section id="leftSection">

	<?php if($is_owner):  ?>
		<div id="options">
			<strong><p>Options</p></strong>
			<div class="optionButton" id="writePost"><p>Write post</p></div>
			<div class="optionButton" id="uploadPicture"><p>Upload picture</p></div>
			<div class "optionButton" id="optionsButton"><p>Change privacy</div></div>
		</div>
		<br>
	<?php endif; ?>

	<div id="info">
		<strong><p>Information</p></strong>
		<p id="location">From: <span id="cityName">New York City</span></p>
		<p>Phone: <span>041821246</span></p>
		<p>Releationship: <span>Single</span></p>
		<p>Occupation: <span>Fashion Model</span></p>
	</div>
	<br>
	<div id="pictures">
		<strong><p>Pictures</p></strong>

		<?php 
 // PREVEČ SLIK!!! 
		?>


	<?php for($i = 0; $i < 6; $i++): ?>
		<?php
		$uploader = User::find_by_id($_SESSION['user_id']);
		$pictureArray = Picture::find_by_uploader($uploader);
		$array_length = sizeof($pictureArray);
		echo($array_length);
		if($i < $array_length):
		  
		 	
		 	$caption = $pictureArray[$i]->caption;
		 	$visibleTo = $pictureArray[$i]->visibleTo;
		 	//echo($visibleTo);
		 	//echo($caption);
		 endif;
		?>
		<img style="width:60px; height:60px; border: 1px solid black" src="<?php echo($profile_url); ?>"> </img>
		<img style="width:60px; height:60px; border: 1px solid black" src="<?php echo($profile_url); ?>"> </img>
		<img style="width:60px; height:60px; border: 1px solid black" src="<?php echo($profile_url); ?>"> </img>
		<br>
			<img style="width:60px; height:60px; border: 1px solid black" src="<?php echo($profile_url); ?>"> </img>
		<img style="width:60px; height:60px; border: 1px solid black" src="<?php echo($profile_url); ?>"> </img>
		<img style="width:60px; height:60px; border: 1px solid black" src="<?php echo($profile_url); ?>"> </img>
	<?php endfor;?>
		<div id="morePicturesButton"><span>More pictures</span></div>
	</div>

	<div id="friends">
		<strong><p>Friends:</p></strong>
		<div></div>
		<div></div>
	</div>

</section>

<section id="rightSection">
	<div id="statusUpdate">
		<form id="statusUpdateForm" name="updateStatus" method="POST" enctype="multipart/form-data" action="private/update_profile.php">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
			<input type="hidden" name="privacySettingID" value="0">
			<input type="file" name="fileUpload" accept="image/*" style="opacity:0" onchange="loadPicture(event)">
			<textarea  name="content" placeholder="Update profile..." cols="40" rows="4"  maxlength="255"></textarea>
			<br>
			<img id="imagePreview"/>
			<br>
			<input type="button" name="addPicture" value="Add picture">
			<input type="button" name="privacySettings" value="Visible to: Friends">
			<?php echo(csrf_token_tag());?>
			<input type="submit" name="send" value="Update" id="submitButton">
		</form>

	   <br>
	   <hr>
	</div>


	<div id="pictureUpload">
		<form method="POST" enctype="multipart/form-data" action="private/save_picture.php">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
			<input type="hidden" name="picturePrivacyID" value="0">
			<input type="file" name="imageUpload" accept="image/*" style="opacity:0;display:none" onchange="loadPictureSecond(event)">
			<img id="imgPreview" />
			<input type="button" name="choosePicture" value="Choose picture" id="choosePic">
			<br>
			<input type="text" name="caption" placeholder="Say something about the picture..">
			<input type="button" name="privacySettingsButton" value="Visible to: Friends">
			<input type="submit" value="Upload" name="uploadPicture">
			<?php echo(csrf_token_tag());?>
			<hr>
		</form>
	</div>
</section>

<!-- END OF WRAPPER -->
</section>

</html>
<?php 	include_once('private/layouts/user_footer_layout.php'); ?>