<?php require_once('init.php'); ?>


<?php
// == CLASS FOR HANDLING MESSAGES == //

class Message {
	public $id;
	public $person_one_id; //Always the sender
	public $person_two_id;
	public $message;
	public $conversation_id;
	public $time;

		// == PERSON ONE ID IS ALWAYS THE ONE THAT SENDS THE MESSAGE == //
		public static function send($person_one_id, $person_two_id, $message) {
			$time = time();
			global $database;
			$message = mysql_real_escape_string($message);
			$query  = "INSERT INTO messages(ID, person_one_id, person_two_id, message, time) VALUES(";
			$query .= "0, '{$person_one_id}', '{$person_two_id}','{$conversation_id}','{$message}', '{$time}')";

			$result = $database->query($query);

			return $result;
		}

		public static function send_as_admin($person_one_id, $recipient_id,$conversation_id, $content) {
			global $database;
			$time = 0;
			$query  = "INSERT INTO messages(ID, person_one_id, person_two_id, conversation_id, message, time) ";
			$query .= "VALUES(0,'{$person_one_id}', '{$recipient_id}', '{$conversation_id}', '{$content}', '{$time}')";
			$result = $database->query($query);
			return $result;
		}


		 public static function find_by_sql($sql="") {
		 	global $database;
		 	$result_set = $database->query($sql);

			$object_array = array();

			while($row = mysqli_fetch_array($result_set,MYSQLI_ASSOC)) {
				$object_array[] = self::init($row);
			}

			return $object_array;
		 }


		 public static function init($row) {
		 	$message = new Message();

		 	$message->id = $row['ID'];
		 	$message->person_one_id = $row['person_one_id'];
		 	$message->person_two_id = $row['person_two_id'];
		 	$message->conversation_id = $row['conversation_id'];
		 	$message->message = htmlspecialchars($row['message']);
		 	$message->time = $row['time'];

		 	return $message;

		 }



	// == SELECTS ALL THE MESSAGES THAT A PAIR IS INVOLVED IN == //

	public static function find_by_participators($person_one_id, $person_two_id) {
		global $database;
		$person_one_id = mysql_real_escape_string($person_one_id);
		$person_two_id = mysql_real_escape_string($person_two_id);
			$query  = "SELECT * FROM messages ";
			$query .= "WHERE person_one_id='".$person_one_id."' AND ";
			$query .= "person_two_id='".$person_two_id."' OR ";
			$query .= "person_one_id='".$person_two_id."' AND ";
			$query .= "person_two_id ='".$person_one_id."'";
			$db_result = $database->query($query);
			$mContainer = array();
			while($row = $db_result->fetch_array(MYSQLI_ASSOC)) {
				$mContainer[] = self::init($row);
			}

			return !empty($mContainer) ? $mContainer : false;
	}

	// == SELECTS ALL THE MESSAGES INVOLVED WITH A PARTICIPATOR == //
	public static function find_by_participator($person_id) {
		global $database;
		$person_id = mysql_real_escape_string($person_id);
		$query  = "SELECT * FROM messages ";
		$query .= "WHERE person_one_id='".$person_id."' OR ";
		$query .= "person_two_id='".$person_id."'";
		$db_result = $database->query($query);
		$mContainer = array();
		while($row = $db_result->fetch_array(MYSQLI_ASSOC)) {
			$mContainer[] = self::init($row);
		}

		return !empty($mContainer) ? $mContainer : false;
	}

	public static function sort_messages($messageContainer) {

	}
}

?>