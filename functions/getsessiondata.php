<?php

session_start();
$name = $_POST['name'];

if (isset($_SESSION[$name])) {
	echo $_SESSION[$name];
}
else {
	echo 'false';
}

?>