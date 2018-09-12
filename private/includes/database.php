
<?php

	class Database {
		public $connection;

		function __construct() {
			$this->connect();
		}

		private function connect() {       
			$this->connection = new mysqli("localhost", "root", "spremenjeno", "socialno_omrezje");
			if(mysqli_connect_errno($this->connection)) {
				echo("Failed to connect to database: " . mysqli_connect_error());
			}

		}
		

		public function query($sql_query) {
			$query_result = $this->connection->query($sql_query);
			if(!$query_result){echo($this->connection->error);}

			return $query_result;
		}



		public function fetch_assoc($result_set) {
			return mysqli_fetch_assoc($result_set);
		}

	

	}

$database = new Database();


?>