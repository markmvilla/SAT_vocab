<?php
// database connection
require_once 'config.php';
$transaction = new dbtransaction();
if(!$transaction->logged_in()) {header('Location: login.php');}
// check for form submition
// if yes build table
if($_POST && (!empty($_POST['importance'])) /*&& (!empty($_POST['recollection']))*/){
	$mini = false;
	$display_navigation_bar = true;
	$title = "List";
	$_SESSION['importance'] = $_POST['importance'];
	$_SESSION['recollection'] = $_POST['recollection'];
	//create content with vocabulary table
	$content = <<<EOF
<table class="vocabulary-table" id="vocabulary-table">
	<thead>
		<tr>
			<th class="column-1 column-word">Word</th>
			<th class="column-2 column-definition">Definition</th>
			<th class="column-3 column-recollection">Progress</th>
			<th class="column-4 column-importance">Importance</th>
			<th class="column-5 column-recollection-input">Update Progress</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
EOF;
}
// if no repost same content
else {
	$mini = true;
	$display_navigation_bar = true;
	$title = "Qualifications";
	$content = <<<EOF
<form action='index.php' method="post">
	<p>
		<label for="importance">Importance Range:</label><br />
		<input type="text" name="importance" class="text" style="color:#888;" value="0 AND 10" />
		<!--
		<label for="recollection">Progress Range:</label><br />
		<input type="text" name="recollection" class="text" style="color:#888;" value="0 AND 10" />
		-->
	</p>
	<p>
		<input type="submit" value="List" />
	</p>
</form>
EOF;
}
// insert content into reusable layout
include 'layout.php';
?>
