<?php
include('config.php');
if(isset($_POST['process']) || isset($_POST['submitData'])) {
  $transaction = new dbtransaction();
  $process = $_POST['process'];
  $submit_data = json_decode($_POST['submitData'], true);

	// processes
	if($process == "firstLoad"){
		if ($transaction->hasRequested()) {
			$transaction->first_load();
		}
	}
	else if($process == "updateDatabaseAndTable"){
		$transaction->update_database_and_table($submit_data);
	}
	else {
    $transaction->sendResponse(400, 'Invalid process request');
    return false;
  }
}
else {
  $transaction->sendResponse(204, 'No content');
  return false;
}
?>
