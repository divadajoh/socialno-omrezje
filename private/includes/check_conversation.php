<?php require_once('init.php'); ?>

<?php
	// == CHECKS IF A CONVERSATION ALREADY EXISTS == //
    // == OR THERE HAS TO BE A NEW ONE CREATED == //

	
	if($_SERVER['REQUEST_METHOD'] == 'POST'):
		if(isset($_POST['pOneID']) && isset($_POST['pTwoID'])):
			$personOneID = $_POST['pOneID'];
			$personTwoID = $_POST['pTwoID'];

			if(!User::exists($personOneID) || !User::exists($personTwoID)):
				header('Location: ../../profile.php?profile_id='.$personOneID);
				// DONT FORGET TO CHECK FOR FRIENDSHIP LATER!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! ///
				exit();
			endif;	


			if(Conversation::exists($personOneID, $personTwoID)):
				$recipient_id = get_chat_partner($personOneID, $personTwoID);
				header('Location: ../../messages.php?recipient_id='.$recipient_id);

			else: // Create new conversation
				$SorF = Conversation::create_conversation($personOneID, $personTwoID);
				if(!$SorF): die('Error'); endif; // On Failure. 
				$recipient_id = get_chat_partner($personOneID, $personTwoID);
				header('Location: ../../messages.php?recipient_id='.$recipient_id);
			endif;

		endif;
	endif;

	function get_chat_partner($pOneID, $pTwoID) {
		if($pOneID == $_SESSION['user_id']):
			return $pTwoID;
		else:
			return $pOneID;
		endif;
	}
?>