<?php require_once('includes/init.php'); ?>
<?php
	$recipient_id = 0;
	$content = "";

	// == HANDLE THE INCOMING MESSAGES AND SAVE THEM IN THE DB== //
	if($_SERVER['REQUEST_METHOD'] == 'POST'):
		if(!(isset($_POST['csrf_token']) && isset($_POST['content']) && isset($_POST['recipientID']))):
			echo('All the form data needs to be filled out!');
			exit();
		endif;

		$recipient_id = mysql_real_escape_string($_POST['recipientID']);

		// == ERROR CHECKING == //
		if(!Conversation::exists($_SESSION['user_id'], $recipient_id)):
			echo("Conversation doesn't exist" . $_SESSION['user_id'] . '  ' . $recipient_id);
			exit();
		endif;

		if(csrf_token_is_recent()):
			if(!($_POST['csrf_token'] == $_SESSION['csrf_token'])):
				echo('CSRF Authentication failed');
				exit();
			endif;
		endif;

		$content = mysql_real_escape_string($_POST['content']);
		$recipient_id = mysql_real_escape_string($_POST['recipientID']);
		$person_one_id = $_SESSION['user_id'];
		$time = time();

		global $database;

		$conversation_id = Conversation::get_conversation_id_by_chatters($_SESSION['user_id'], $recipient_id);


		$query  = "INSERT INTO messages(ID, person_one_id, person_two_id, conversation_id, message, time) ";
		$query .= "VALUES(0,'{$person_one_id}', '{$recipient_id}', '{$conversation_id}', '{$content}', '{$time}')";
		$result = $database->query($query);

		$query  ="UPDATE conversations ";
		$query .="SET last_chat_time='{$time}' ";
		$query .= "WHERE ID='{$conversation_id}'";
		$aResult = $database->query($query);


		// true if success
		if($result && $aResult):
			echo('true' . 'true');
		else:
			echo('false');
		endif;
		


		
	endif;

?>