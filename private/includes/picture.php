<?php require_once("init.php"); ?>

<?php
	class Picture {
		public $ID;
		public $Uploader_ID;
		public $url;
		public $caption;
		public $visibleTo;
		
		

		public static function create($uploader_ID, $url, $caption, $visibleTo) {
			global $database;
			$query  = "INSERT INTO pictures(ID, Uploader_ID, url, caption, visibleTo) VALUES(";
			$query .= "0, '{$url}', '{$uploader_id}', '{$visible_to}', '{$description}')";

			$result = $database->query($query);

			return $result;
		}

		public static function find_all() {
		 self::find_by_sql("SELECT * FROM pictures");
		 }

		 public static function find_by_id($id=0) {
		 	$result_array =  self::find_by_sql("SELECT * FROM pictures WHERE ID={$id} LIMIT 1");

		 	return !empty($result_array) ? array_shift($result_array) : false;
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


		 public static function find_by_uploader($uploader) {
		 	global $database;

		 	$query ="SELECT * FROM pictures WHERE Uploader_ID =" . $uploader->id;
		 	$object_array = self::find_by_sql($query);

		 	return sizeof($object_array) > 0 ? $object_array : false;

		 }

		 public static function get_profile_picture_url($user_id) {
		 	global $database;

		 	$query = "SELECT ID, Profilna_Slika FROM uporabniki WHERE ID = ".$user_id;
		 	$result_set = $database->query($query);
		 	return mysqli_fetch_array($result_set, MYSQLI_ASSOC);
		 }

		 public static function init($row) {
		 	$slika = new Picture();

		 	$slika->id = $row['ID'];
		 	$slika->url = $row['Uploader_ID'];
		 	$slika->uploader_id = $row['url'];
		 	$slika->visibleTo = $row['visibleTo'];
		 	$slika->caption = $row['caption'];

		 	return $slika;

		 }
	}

?>