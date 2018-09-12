<?php require_once('init.php'); ?>
<?php 
	if($session->is_logged_in()) 
	{
		$session->logout();
		header('Location: index.php');
	}
?>