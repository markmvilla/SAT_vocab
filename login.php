<?php
require_once 'config.php';
$transaction = new dbtransaction();
if($transaction->logged_in()) {header('Location: index.php');}
$display_navigation_bar = false;
$mini = true;
$title = "Login";
if($_POST && (!empty($_POST['username'])) && (!empty($_POST['password']))) {
	$transaction->validate_user($_POST['username'], $_POST['password']);
}
$message = $_SESSION['error'];
$content = <<<EOF
$message
<form action='login.php' method="post">
	<p>
		<label for="username">username:</label><br />
		<input type="text" name="username" class="text" />
	</p>
	<p>
		<label for="password">password:</label><br />
		<input type="password" name="password" class="text" />
	</p>
	<p>
		<input type="submit" value="Login" />
	</p>
</form>
<span class="right">
	<a href="register.php">Register</a>
</span>
EOF;
include 'layout.php';
?>
