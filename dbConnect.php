<?php
class dbtransaction {
  private $dbc;
	// disconnect
  function __construct() {
    $this->dbc = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
    if ($this->dbc->connect_error) {
      echo $this->dbc->connect_error;
    }
		//$this->dbc->query("SET time_zone = 'US/Central'");
  }

	// transaction methods
	function pull_query($query){
		$result = $this->dbc->query($query);
		$fetchedresult = $result->fetch_all(MYSQLI_ASSOC);
		return $fetchedresult;
	}
	function push_query($query){
		$this->dbc->query($query);
	}

	// credentials
	function register_user($user_name, $user_password) {
		$luser_name = strtolower($user_name);
		$_SESSION['error'] = "";
		if (preg_match('/^(?=.*[a-z])(?=.*[A-Z]).+$/', $user_password) && preg_match('/^[a-zA-Z0-9,!;\-\?]+$/', $user_password)) {
			if (preg_match('/^[a-z]+$/', $luser_name)) {
				$isDuplicate = $this->pull_query("SELECT * FROM users WHERE user_name = '$luser_name'");
				if (count($isDuplicate) <= 0) {
					$initial_recollection = str_repeat('0',5000);
					$this->push_query("INSERT INTO users (user_name, user_password, user_recollection) VALUES ('$luser_name','$user_password', '$initial_recollection')");
					$this->validate_user($luser_name, $user_password);
				} else {
					$_SESSION['error'] = "Username is already taken.";
					header('Location: register.php');
				}
			} else{
				$_SESSION['error'] = "Username should not have special characters.";
				header('Location: register.php');
			}
		} else {
			$_SESSION['error'] = "Password must contain at least one uppercase letter, one lower case letter and no special characters.";
			header('Location: register.php');
		}
	}
	function validate_user($user_name, $user_password) {
		if($this->check_username_and_pw($user_name, $user_password)) {
			header('Location: index.php');
		} else {
			$_SESSION['error'] = "Login failed.";
			header('Location: login.php');
		}
	}
	function logged_in() {
		if($_SESSION['authorized'] == true) {
			return true;
		} else {
			return false;
		}
	}
	function check_username_and_pw($user_name, $user_password) {
		$isValid = $this->pull_query("SELECT * FROM users WHERE user_name = '$user_name' AND user_password = '$user_password'");
		if($isValid) {
			$_SESSION['authorized'] = true;
			$_SESSION['username'] = $user_name;
			return true;
		}
		else {
			return false;
		}
	}
	function hasRequested() {
		if($this->logged_in() && (isset($_SESSION['importance'])/* || isset($_SESSION['recollection'])*/)) {
			return true;
		} else {
			return false;
		}
	}

	// querying
	function first_load() {
		$importance = $_SESSION['importance'];
		$user_name = $_SESSION['username'];
		$table = $this->pull_query("SELECT * FROM vocabulary WHERE importance BETWEEN $importance ORDER BY id ASC");
		$recollection_list = $this->pull_query("SELECT user_recollection FROM users WHERE user_name = '$user_name'");
		// for all outputs, insert into table the recollection char where index is id minus one
		for ($i = 0; $i < count($table); $i++) {
			$table[$i]['progress'] = $recollection_list[0]['user_recollection'][$table[$i]['id']-1];
		}
		$this->sendResponse(200, $table);
	}
	function update_database_and_table($new_recollection_input) {
		$user_name = $_SESSION['username'];
		$old_recollection_list = $this->pull_query("SELECT user_recollection FROM users WHERE user_name = '$user_name'");
		// for all inputs, if they are numeric, insert into string where index is input id minus one
		for ($i = 0; $i < count($new_recollection_input); $i++) {
			if(preg_match('/^[0-9]+$/', $new_recollection_input[$i]['input'])) {
				$old_recollection_list[0]['user_recollection'][$new_recollection_input[$i]['id']-1] = $new_recollection_input[$i]['input'];
			}
		}
		$new_recollection_list_array = $old_recollection_list[0]['user_recollection'];
		$this->push_query("UPDATE users SET user_recollection = '$new_recollection_list_array' WHERE user_name = '$user_name'");
		$this->first_load();
	}

	// render error messages
	function error_messages() {
	    $message = '';
	    if($_SESSION['success'] != '') {
	        $message = '<span class="success" id="message">'.$_SESSION['success'].'</span>';
	        $_SESSION['success'] = '';
	    }
	    if($_SESSION['error'] != '') {
	        $message = '<span class="error" id="message">'.$_SESSION['error'].'</span>';
	        $_SESSION['error'] = '';
	    }
	    return $message;
	}

	// HTTP status code and response
  function getStatusCodeMessage($status) {
    // these could be stored in a .ini file and loaded
    // via parse_ini_file()... however, this will suffice
    // for an example
    $codes = Array(
      100 => 'Continue',
      101 => 'Switching Protocols',
      200 => 'OK',
      201 => 'Created',
      202 => 'Accepted',
      203 => 'Non-Authoritative Information',
      204 => 'No Content',
      205 => 'Reset Content',
      206 => 'Partial Content',
      300 => 'Multiple Choices',
      301 => 'Moved Permanently',
      302 => 'Found',
      303 => 'See Other',
      304 => 'Not Modified',
      305 => 'Use Proxy',
      306 => '(Unused)',
      307 => 'Temporary Redirect',
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      405 => 'Method Not Allowed',
      406 => 'Not Acceptable',
      407 => 'Proxy Authentication Required',
      408 => 'Request Timeout',
      409 => 'Conflict',
      410 => 'Gone',
      411 => 'Length Required',
      412 => 'Precondition Failed',
      413 => 'Request Entity Too Large',
      414 => 'Request-URI Too Long',
      415 => 'Unsupported Media Type',
      416 => 'Requested Range Not Satisfiable',
      417 => 'Expectation Failed',
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
      502 => 'Bad Gateway',
      503 => 'Service Unavailable',
      504 => 'Gateway Timeout',
      505 => 'HTTP Version Not Supported'
    );
    return (isset($codes[$status])) ? $codes[$status] : '';
  }
  function sendResponse($status, $body) {
    $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMessage($status);
    header($status_header);
    header('Content-type: text/json charset=utf-8');
		echo json_encode($body);
  }

	// disconnect
	function __destruct() {
		$this->dbc->close();
	}
}
?>
