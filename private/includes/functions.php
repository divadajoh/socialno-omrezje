
<?php
	function show_error($error_text) {
		header('Location: private/error_report.php?message='.urlencode($error_text));
	}
?>