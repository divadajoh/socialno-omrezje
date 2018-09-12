<?php
	require_once('init.php');

	class User {
		public $id;
		public $ime;
		public $priimek;
		public $email;
		public $profile_picture; //URL
		public $phone_number;
		public $location;
		public $interests;
		public $first_timer;


		public static function find_all() {
		 return self::find_by_sql("SELECT * FROM {$ime_tabele}");
		 }

		 public static function find_by_id($id=0) {
		 	$result_array =  self::find_by_sql("SELECT * FROM uporabniki WHERE ID={$id} LIMIT 1");

		 	return !empty($result_array) ? array_shift($result_array) : false;
		 }


		 //Ustvari->User-> objekt in mu dodeli atribute.
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
		 	$user = new User();

		 	$user->id = $row['ID'];
		 	$user->ime = $row['Ime'];
		 	$user->priimek = $row['Priimek'];
		 	$user->email = $row['Email'];
		 	$user->phone_number = $row['Phone_Number'];
		 	$user->location = $row['Location'];
		 	$user->profile_picture = $row['Profilna_Slika'];
		 	$user->interests = $row['Interests'];
		 	$user->first_timer = $row['first_timer'];

		 	return $user;

		 }

		 public static function authenticate($username, $password) {
		 	global $database;
		 	//Escaping has already been done in login.php // 
		 	
		 	$sql =  "SELECT * FROM uporabniki ";
		 	$sql .= "WHERE Email = '{$username}' ";
		 	$sql .= "AND Geslo = '{$password}' ";

		 	$user_array = self::find_by_sql($sql);


		 	return !empty($user_array) ? array_shift($user_array) : false;

		 }

		 public static function check_friendship($friend_one_id, $friend_two_id) {
		 	global $database;

		 	$query = "SELECT * FROM friendships WHERE friend_one_id = '{$friend_one_id}' AND friend_two_id = '{$friend_two_id}' OR friend_two_id = '{$friend_one_id}' AND friend_one_id = '{$friend_two_id}'";
		 	$result_set = $database->query($query);
		 	$row = mysqli_fetch_array($result_set,MYSQLI_ASSOC);

		 	return $row; 
		 }

		 public static function exists($user_id) {
		 	global $database;
		 	$query ="SELECT * FROM uporabniki WHERE ID = '{$user_id}'";
		 	$result = $database->query($query);

		 	return (mysqli_num_rows($result) > 0) ? true : false;
		 }

		 public function friends() {
		 	return Friendship::get_friends_by_user($this->id);
		 }

		 public function has_friend($friend_id) {
		 	return Friendship::check_friendship($this->id, $friend_id);
		 }


	 

	}

?>

