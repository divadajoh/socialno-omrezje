<?php require_once('includes/init.php'); ?>

<?php
	$iContainer = array();

	if($_SERVER['REQUEST_METHOD'] == 'POST'):
		$recipient_id = $_POST['recipientID'];
		$sender_id = $_SESSION['user_id'];

		$conversation = Conversation::get_conversation_by_participators($sender_id, $recipient_id);
		if(!$conversation): exit(); endif;


		$conversationIndentifier = "conversation".$conversation->id;
		// == CREATE A IDENTIFIER IN THE SESSION WHICH WILL ==
		// == STORE A VARIABLE OF THE LAST KNOWN NUMBER OF MESSAGES IN THIS ==
		// == CONVERSATION, SO THAT THE SERVER DOESNT NEED TO CHECK ==
		// == THE DATABASE EACH TIME IT WANTS TO FIND OUT IF THERE ARE ==
		// == ANY NEW  MESSAGES, BUT RATHER DO IT WHEN HE IS CERTAIN == //
		// == THAT THERE ARE NEW MESSAGES == //

		if(!isset($_SESSION[$conversationIndentifier])):
			//Set new identifier.
			$_SESSION[$conversationIndentifier] = sizeof($conversation->messages);
			exit();
		else:
			$old_number_of_messages = $_SESSION[$conversationIndentifier];
			$new_number_of_messages = sizeof($conversation->messages);
			$old_message = $conversation->messages[$old_number_of_messages-1];
			$old_message->message = 'lastMessage';
			
			if($new_number_of_messages > $old_number_of_messages):
				$new_message_number = $new_number_of_messages - $old_number_of_messages;
				$messages = $conversation->messages;
				array_push($iContainer, $old_message);
				for($i = sizeof($messages)-1; $i >= $old_number_of_messages; $i--):
					array_push($iContainer, $messages[$i]);
				endfor;

				$_SESSION[$conversationIndentifier] = $new_number_of_messages;
				print json_encode($iContainer);

			elseif($new_number_of_messages < $old_number_of_messages):
				$_SESSION[$conversationIndentifier] = sizeof($conversation->messages);
			endif;
		endif;

		


	endif;
?>