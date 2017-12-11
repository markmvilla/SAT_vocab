<?php
require_once 'config.php';
$transaction = new dbtransaction();
if($transaction->logged_in()) {header('Location: index.php');}
$display_navigation_bar = false;
$mini = true;
$title = "Register";
if($_POST && (!empty($_POST['username'])) && (!empty($_POST['password']))) {
	$transaction->register_user($_POST['username'], $_POST['password']);
}
$message = $_SESSION['error']. '<br>';
$label = '<label>Choose a username and password.</label>';
$content = <<<EOF
$message
$label
<form action='register.php' method="post">
	<p>
		<label for="username">username:</label><br />
		<input type="text" name="username" class="text" />
	</p>
	<p>
		<label for="password">password:</label><br />
		<input type="text" name="password" class="text" />
	</p>
	<p>
		<input type="submit" value="Register" />
	</p>
</form>
EOF;
include 'layout.php';
?>
