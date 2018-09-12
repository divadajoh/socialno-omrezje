<?php require_once('init.php'); ?>

<?php
	class Photo {
		public $id;
		public $Uploader_ID;
		public $url;
		public $caption;
		public $visibleTo;

		public static function create($user_id, $url,  $caption, $visibleTo) {
			global $database;

			$query  = "INSERT INTO pictures(Uploader_ID, url, caption,visibleTo) ";
			$query .= "VALUES('{$user_id}', '{$url}','{$caption}','{$visibleTo}')";
			$result = $database->query($query);	


			// == FIND AND RETURN THE LAST INSERTED ROW(ID) == //
			$query = "SELECT ID FROM pictures WHERE Uploader_ID = '{$user_id}' ORDER BY ID DESC LIMIT 1";
			$result = $database->query($query);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			return $row['ID'];
		}

		public static function init($row) {
		 	$photo = new Photo();

		 	$photo->id = htmlspecialchars($row['ID']);
		 	$photo->Uploader_ID = htmlspecialchars($row['Uploader_ID']);
		 	$photo->url =  htmlspecialchars($row['url']);
		 	$photo->caption = htmlspecialchars($row['caption']);
		 	$photo->visibleTo = htmlspecialchars($row['visibleTo']);
		 	return $photo;

		 }
	}
?>