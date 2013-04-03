<?php
	session_start();
	require_once '../classes/users.php';
	$users = new users();
	if($_POST && !empty($_POST['name']) && !empty($_POST['pass'])){
		$users->validate_user($_POST['name'], $_POST['pass']);
	}
?>