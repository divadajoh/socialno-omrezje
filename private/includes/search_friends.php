<?php require_once('init.php'); ?>

<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST['search_string']) && !empty($_POST['search_string'])):

			$data = mysql_real_escape_string($_POST['search_string']);
			$friends = Friendship::get_friends_by_user($_SESSION['user_id']);

			$query  = "SELECT ID, Ime, Priimek,Profilna_Slika FROM uporabniki WHERE Ime LIKE '".$data."%' ";
			$query .= "OR Priimek LIKE '".$data."%'";

			global $database;
			$result_set = $database->query($query);

				$rows = array();
				while($r = mysqli_fetch_assoc($result_set)) {
					// == CHECK IF THE USER IS ACCTUALY A FRIEND == //
					if(!in_array($r['ID'], $friends)):
						continue;
					endif;
					$r['Ime'] = htmlspecialchars($r['Ime']);
					$r['Priimek'] = htmlspecialchars($r['Priimek']);
					$r['Profilna_Slika'] = htmlspecialchars($r['Profilna_Slika']);
    				$rows[] = $r;
				}
				
				print json_encode($rows);

		endif;
	}
?>