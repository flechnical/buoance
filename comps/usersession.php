<?php
ini_set('session.gc_probability', 0);
session_start();
if(isset($_SESSION['keepin']) && $_SESSION['keepin'] == 'on'){
	setcookie(session_name(), $_COOKIE[session_name()], time() + 60*60*24*30, '/'); // bei jedem Aufruf eines eingeloggten Users soll der Cookie auf ein Monat gesetzt werden
}
require_once $_SERVER['DOCUMENT_ROOT'].'/classes/users.php';
$users = new users();
$_SESSION['ref'] = $_SERVER['REQUEST_URI'];
?>