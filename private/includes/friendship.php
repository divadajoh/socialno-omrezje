<?php require_once('init.php'); ?>
<?php
	// == STATIC CLASS FOR HANDLING FRIENDSHIPS == //
	class Friendship{

		public static function create_friendship($user_id, $friend_id) {
			global $database;
			$query  = "INSERT INTO friendships(ID, user_id, friend_id) VALUES(0, '{$user_id}','{$friend_id}')";
			$resultOne = $database->query($query);
			$query  = "INSERT INTO friendships(ID, user_id, friend_id) VALUES(0, '{$friend_id}','{$user_id}')";
			$resultTwo = $database->query($query);

			return ($resultOne && $resultTwo) ? true : false;
		}

		public static function check_friendship($user_id, $friend_id) {
			global $database;
			$query  = "SELECT * FROM friendships WHERE ";
			$query .= "user_id = '{$user_id}' AND friend_id = '{$friend_id}'";
			$result = $database->query($query);
			$row_number = mysqli_num_rows($result);
			return ($row_number > 0) ? true : false;
		}

		public static function send_friend_request($sender_id, $receiver_id) {
			global $database;
			$query  = "INSERT INTO friend_requests VALUES(";
			$query .= "0,'{$sender_id}', '{$receiver_id}')";
			$result = $database->query($query);

			return $result;
		}

		public static function get_pending_friend_requests_by_user($user_id) {
			global $database;
			$query = "SELECT * FROM friend_requests WHERE receiver_id = '{$user_id}'";
			$result = $database->query($query);
			$result_array = $result->fetch_array(MYSQLI_ASSOC);
			return (sizeof($result_array) > 0) ? $result_array : false;
		}

		public static function accept_friend_request($request_sender_id, $request_sender_id) {
			global $database;
			$query  = "DELETE FROM friend_requests WHERE sender_id = '{$request_sender_id}'";
			$query .= "AND reciever_id = '{$request_receiver_id}'";
			$result = $database->query($query);

			return Self::create_friendship($request_sender_id, $request_receiver_id);

		}

		public static function friend_request_exists($sender_id, $receiver_id) {
			global $database;
			$query  = "SELECT * FROM friend_requests WHERE sender_id = '{$sender_id}' ";
			$query .= "AND receiver_id = '{$receiver_id}'";
			$result = $database->query($query);
			$result_array = $result->fetch_array(MYSQLI_ASSOC);
			return (sizeof($result_array) > 0) ? true : false;
		}

		public static function get_friends_by_user($user_id) {
			global $database;
			$query = "SELECT friend_id FROM friendships WHERE user_id = '{$user_id}' ";
			$result = $database->query($query);
			$row_number = mysqli_num_rows($result);

			return ($row_number > 0) ? $result->fetch_array(MYSQLI_ASSOC) : false;

		}

	}
?>