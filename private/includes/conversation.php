<?php require_once('init.php'); ?>

<?php
	class Conversation {
		public $id;
		public $personOne;
		public $personTwo;
		public $conversationID;
		public $lastChatTime;
		public $messages = array();



		// == CHECKS IF A ROW WITH THOSE ID'S ALREADY EXISTS IN CONVERSATION TABLE == //
		public static function exists($person_one_id, $person_two_id) {
			global $database;
			$query  = "SELECT * FROM conversations ";
			$query .= "WHERE person_one_id='".$person_one_id."' AND ";
			$query .= "person_two_id='".$person_two_id."' OR ";
			$query .= "person_one_id='".$person_two_id."' AND ";
			$query .= "person_two_id ='".$person_one_id."'";
			$result = $database->query($query);

			return (mysqli_num_rows($result) > 0) ? true : false;
		}

		// == CREATES A NEW CONVERSATION (THE FIRST TIME 2 PEOPLE TALK) == //
		public static function create_conversation($person_one_id, $person_two_id){
			global $database;
			$lastChatTime = time();
			$query  = "INSERT INTO conversations(ID, person_one_id, person_two_id, last_chat_time) ";
			$query .= "VALUES(0,'".$person_one_id."','".$person_two_id."', '{$lastChatTime}')";
			$result = $database->query($query);
			return ($result) ? true : false;
		}

		// == RETURNS A ARRAY OF CONVERSATION OBJECTS IN WHICH THE USER PARTICIPATES == //
		public static function get_conversations_by_participator($personID) {
			global $database;
			$query  = "SELECT * FROM conversations ";
			$query .= "WHERE person_one_id ='{$personID}' ";
			$query .="OR person_two_id = '{$personID}' ";
			$query .="ORDER BY last_chat_time DESC";
			$result=$database->query($query);
			if(!$result): return false; endif;

			$conversationContainer = array();

			while($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$conversation = new Conversation();
				$pOneID = $row['person_one_id'];
				$pTwoID = $row['person_two_id'];
				$pOne = User::find_by_id($pOneID);
				$pTwo = User::find_by_id($pTwoID);
				$conversation->id = $row['ID'];
				$conversation->personOne = $pOne;
				$conversation->personTwo = $pTwo;
				$conversation->lastChatTime = $row['last_chat_time'];

				// == RETURNS ALL THE MESSAGES BETWEEN 2 PARTICIPATORS == //
				$messages = Message::find_by_participators($pOneID, $pTwoID);
				$conversation->messages = $messages;

				array_push($conversationContainer, $conversation);
			}

			return (sizeof($conversationContainer) > 0) ? $conversationContainer : false;
		}

		// == RETURNS A ARRAY OF CONVERSATION OBJECTS IN WHICH BOTH USERS PARTICIPATES == //
		public static function get_conversation_by_participators($person_one_id, $person_two_id) {
			global $database;
			$query  = "SELECT * FROM conversations ";
			$query .= "WHERE person_one_id='".$person_one_id."' AND ";
			$query .= "person_two_id='".$person_two_id."' OR ";
			$query .= "person_one_id='".$person_two_id."' AND ";
			$query .= "person_two_id ='".$person_one_id."' ";
			$query .="ORDER BY last_chat_time DESC";
			$result=$database->query($query);
			if(!$result): return false; endif;

			$conversationContainer = array();

			while($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$conversation = new Conversation();
				$pOneID = $row['person_one_id'];
				$pTwoID = $row['person_two_id'];
				$pOne = User::find_by_id($pOneID);
				$pTwo = User::find_by_id($pTwoID);
				$conversation->id = $row['ID'];
				$conversation->personOne = $pOne;
				$conversation->personTwo = $pTwo;
				$conversation->lastChatTime = $row['last_chat_time'];

				// == RETURNS ALL THE MESSAGES BETWEEN 2 PARTICIPATORS == //
				$messages = Message::find_by_participators($pOneID, $pTwoID);
				$conversation->messages = $messages;

				array_push($conversationContainer, $conversation);
			}

			return (sizeof($conversationContainer > 0)) ? array_shift($conversationContainer) : false;
		}


		// == RETURNS A CONVERSATION OBJECT IN WHICH PARTICIPANTS ARE TOGETHER == //
		public static function get_conversation_id_by_chatters($person_one_id, $person_two_id) {
			global $database;
			$query  = "SELECT ID FROM conversations ";
			$query .= "WHERE person_one_id='".$person_one_id."' AND ";
			$query .= "person_two_id='".$person_two_id."' OR ";
			$query .= "person_one_id='".$person_two_id."' AND ";
			$query .= "person_two_id ='".$person_one_id."'";
			$result = $database->query($query);
			$result_array = $result->fetch_array(MYSQLI_ASSOC);	
			if(sizeof($result_array) > 0): 
				return array_shift($result_array);
			else: 
				return false; 
			endif;
		}

		// == DELETES A CONVERSATION == //
		public static function delete($person_one_id, $person_two_id) {
			global $database;
			$query  = "DELETE FROM conversations ";
			$query .= "WHERE person_one_id='".$person_one_id."' AND ";
			$query .= "person_two_id='".$person_two_id."' OR ";
			$query .= "person_one_id='".$person_two_id."' AND ";
			$query .= "person_two_id ='".$person_one_id."'";
			$result = $database->query($query);

			$query  = "DELETE FROM messages ";
			$query .= "WHERE person_one_id='".$person_one_id."' AND ";
			$query .= "person_two_id='".$person_two_id."' OR ";
			$query .= "person_one_id='".$person_two_id."' AND ";
			$query .= "person_two_id ='".$person_one_id."'";
			$result = $database->query($query);
			return $result ? true : false;


		}

		function get_messages_by_chat_partner($conversationArray, $recipient_id) {
			foreach($conversationArray as $conversation):
				if($conversation->personOne->id == $recipient_id || $conversation->personTwo->id == $recipient_id):
					return $conversation->messages;
				endif;
			endforeach;

			return false;
		}


        // == RETURNS AN OBJECT OF THE PERSON THE USER IS CHATTING WITH == //
		public function get_chat_partner() {
			$pOneID = $this->personOne->id;
			$pTwoID = $this->personTwo->id;

			if($pOneID == $_SESSION['user_id']):
				return $this->personTwo;
			else:
				return $this->personOne;
			endif;
		}
		// == RETURNS THE ID OF THE LAST PERSON THE USER CHATTED WITH == //
		function get_last_chat_partner_id($user_id) {
			global $database;
			$query = "SELECT * FROM messages WHERE ";
			$query .= "person_one_id = '{$user_id}' ";
			$query .= "OR person_two_id = '{$user_id}' ";
			$query .= "ORDER BY time DESC LIMIT 1";
			$result = $database->query($query);
			$assoc_array = $result->fetch_array(MYSQLI_ASSOC);
			$conversation_id = $assoc_array['conversation_id'];
			$num_rows = mysqli_num_rows($result);
			if(sizeof($num_rows) > 0):
				$query = "SELECT * FROM conversations WHERE ";
				$query .= "ID='{$conversation_id}' LIMIT 1";
				$result = $database->query($query);
				$assoc_array = $result->fetch_array(MYSQLI_ASSOC);
				$num_rows = mysqli_num_rows($result);
			
				if(sizeof($assoc_array) < 1): return false; endif;

				if($assoc_array['person_one_id'] == $user_id):
					return $assoc_array['person_two_id'];
				else:
					return $assoc_array['person_one_id'];
				endif;

			endif;
		}


	}
?>