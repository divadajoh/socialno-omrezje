<?php require_once('private/includes/init.php'); ?>
<?php include_once('private/layouts/user_header_layout.php');?>
<?php if(!$session->is_logged_in()){header('Location: index.php?err_id=2');}?>

<?php
	$user_id = $_SESSION['user_id'];
	$flag = 0;
	$messageCounter = 0;

	$recipient_id = urlencode($_GET['recipient_id']);
	$recipient = User::find_by_id($recipient_id);
	// ============= CONVERSATIONS ============= //
	
	// Checking for data manipulation
	if(!isset($_GET['recipient_id'])):
		header('Location: private/error_report.php');
	endif;

	if(!User::exists($_GET['recipient_id'])):
		header('Location: private/error_report.php');
	 endif;
	if(!Conversation::exists($_GET['recipient_id'], $_SESSION['user_id'])):
		header('Location: private/error_report.php');
	 endif;
	if($recipient_id==$_SESSION['user_id']): 
		header('Location: private/error_report.php');
	 endif;
	if(!Friendship::check_friendship($_SESSION['user_id'], $_GET['recipient_id'])):
		header('Location: private/error_report.php');
	endif;

	if(isset($_GET['delete']) && $_GET['delete'] == 'true'):
		Conversation::delete($_SESSION['user_id'], $_GET['recipient_id']);
		$tempID = Conversation::get_last_chat_partner_id($_SESSION['user_id']);
		$tempURL = "messages.php?recipient_id=".$tempID;
		header('Location: '.$tempURL);
	endif;
	 

	$conversations = Conversation::get_conversations_by_participator($user_id);
	if(empty($conversations)): $flag = 1; endif; // No conversations exist.

	// ============= MESSAGES ============= //
	if(!$recipient): /* ERROR */ endif;
	$messages = Conversation::get_messages_by_chat_partner($conversations, $recipient_id);
	if(sizeof($messages) < 2): unset($messages); $messages = array(); endif;

?>

<!DOCTYPE html>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="private/scripts/messages_script.js"></script>
		<link rel="stylesheet" href="private/styles/messages_style.css">
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	</head>


	<second>

		<section id="wrapper">
			<h1 >Messages</h1>
			<div id="contactContainer">
				<div id="newConversationButton"><p>Start new conversation</p></div>
				<?php foreach($conversations as $conversation): ?>
					<?php 
						$addedClassName="";
						$chatPartner = $conversation->get_chat_partner();

						// If the current partner object id is equal to the url value of recipient_id.
						if($chatPartner->id == $recipient_id): $addedClassName="selected"; endif; 
						$pPicName= $chatPartner->profile_picture;
						$profile_pic_url = "/hojawebsite/private/users/" . $chatPartner->id ."/". $pPicName;
						$profile_link = "location.href='messages.php?recipient_id=".$chatPartner->id."'";
					?>
					<div class="contact <?php echo($addedClassName); ?>" onclick="if(!triggerConversationDelete){<?php echo($profile_link); ?>}">
						<img src="<?php echo($profile_pic_url); ?>"></img>
						<span> <?php echo(htmlspecialchars($chatPartner->ime) .' '. htmlspecialchars($chatPartner->priimek)) ?> </span>
						<img src="public_images/cancel_delete_icon.png" id="mDelete" style="display:none"></img>
					</div>
				<?php endforeach; ?>
				
			</div>

			<div id="messageContainer">

				<div class="messageArea">

					<?php $previousMessage = new Message(); $previousMessage->id=4546456564; ?>
					<?php foreach($messages as $message): ?>
						<?php
							$messageCounter++;
							$addedClassName = "";
							$msgClassName = "";
							$seperatorFlag = false;
							$chatPartner = User::find_by_id($message->person_one_id);
							$pPicName = $chatPartner->profile_picture;
							$profile_pic_url = "/hojawebsite/private/users/" . $chatPartner->id ."/". $pPicName;
							$fullname = $chatPartner->ime . ' ' . $chatPartner->priimek;

							if(sizeof($messages) == $messageCounter) :
								$msgClassName .= ' last'; 
								$seperatorFlag = true;
							endif;

						
						?>
						<?php if($previousMessage->person_one_id != $message->person_one_id): ?>
						<div class='msgbox'>
							<img src="<?php echo($profile_pic_url); ?>"></img>
							<span><?php echo($fullname); ?></span>
						</div>
						<?php endif; ?>

						<span class='message' id="<?php echo($message->id); ?>"><?php echo($message->message); ?> </span>
						<?php if($seperatorFlag): echo('<p></p>'); endif; ?>
					<?php  $previousMessage = $message; endforeach; ?>

				</div>

				<form id="messageForm" name="messageForm" id="mForm">
					<input type="submit" name="submit" value="Send" title="Send message">
					<?php echo(csrf_token_tag());?>
					<input type="hidden" name="recipientID" id="recID" value="<?php echo($recipient_id); ?>">
				</form>
				<input form="messageForm" type="textarea" name="content" placeholder="Write message" cols="40" rows="4"  maxlength="510">

				<form action="private/includes/check_conversation.php" id='chkConv' method="POST">
					<input type="hidden" name="pOneID" value="<?php echo($_SESSION['user_id']);?>">
					<input type="hidden" name="pTwoID">
					<input type="submit" hidden>
				</form>

			</div>
		</section>
		<?php include_once('private/layouts/user_footer_layout.php'); ?>
	</body>
</html>