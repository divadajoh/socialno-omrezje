<?php require_once('includes/init.php'); ?>

<?php
	// == HANDLES THE INCOMING PROFILE-RELATED REQUESTS == //
	if($_SERVER['REQUEST_METHOD'] == 'POST'):

		if(isset($_POST['Friend_Request'])):
			$receiver_id = mysql_real_escape_string($_POST['Friend_Request']);
			$sender_id = $_SESSION['user_id'];
			$sender = User::find_by_id($sender_id);
			$friend_request_exists = Friendship::friend_request_exists($sender_id, $receiver_id);

			if(!$sender->has_friend($receiver_id) && !$friend_request_exists ):
				if(Friendship::send_friend_request($sender_id, $receiver_id)):
					echo('FriendRequestSent');
				else:
					echo('FriendRequestFailed');
				endif;
			else:
				echo('FriendRequestFailed');
			endif;

		endif;
	endif;	
?>