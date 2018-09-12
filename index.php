<?php require_once('private/includes/init.php'); ?>

<?php if($session->is_logged_in()){header('Location: home.php');} ?>

	<?php $err_message = "";if(isset($_GET['err_id'])):

		// == LOGIN ERROR MESSAGE == //
			if($_GET['err_id'] == 1):
				$err_message = "*Invalid <span style='color:white'>Email/Password</span> combination.";
			elseif($_GET['err_id'] == 2):
				$err_message = "*You need to be logged in to do that.";
			endif;

		endif;
		
	?>

	<!DOCTYPE HTML>
	<html>
		<head>
			<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600italic' rel='stylesheet' type='text/css'>
			<link rel="stylesheet" type="text/css" href="style/index_style.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
			<script src="script/index_script.js"></script> 
			<script src="script/form_validation.js"></script>
		</head>

		<body>
			<header>
				<div>
					<p>Contact</p>
				</div>
			</header>

			<p id="loginErrMessage"> <?php echo($err_message); ?></p>

			<section class="loginForm">
				<p style="height: 1px"></p>
				<h2 class="loginHeader">Login </h2>

				<form action="private/includes/login.php" method="POST">
					<?php echo(csrf_token_tag()); ?>
					<p>E-mail</p>
					<input type="text" name="emailInput" id="emailBox">
					<p>Password</p>
					<input type="password" name="passwordInput" id="passwordBox">
					<br>
					<input type="submit" name="loginButton" class="submitButton" value="Log in">
					<br>
					<p style="height: 7px"></p>
					<a onclick="scrollDownToRegister()">Not registered yet?</a> 
				</form>
			</section>

			<h2 class="registerTitle"></h2>

			<p class="registerEmailErrMessage">*Your <span style="color:red">email adress</span> is not valid.</p>
			<p class="registerPassErrMessage">*<span style="color:red">Passwords</span> do not match! Check your spelling.</p>


			<section id="registerForm">
				<h2 class="registerHeader">Register</h2>

				<form id="registrationForm">
					<p>Name</p>
					<input type="text" name="nameInput" class="registerInputBox" id="nField" maxlength="12">
					<p>Surname</p>
					<input type="text" name="surnameInput" class="registerInputBox" id="snField" maxlength="12">
					<p>Email</p>
					<input type="text" name="emailInput" class="registerInputBox" id="rEmailInput">
					<p>Password</p>
					<input type="password" name="passwordInput" class="registerInputBox" id="pass">
					<p>Confirm password</p>
					<input type="password" name="confirmPasswordInput" class="registerInputBox" id="cPass">
					<br>
					<input type="submit" name="submitButton" value="Register" class="submitButton">
				</form>

			</section>

			<div class="emptyDiv"></div>
		</body>
	</html>