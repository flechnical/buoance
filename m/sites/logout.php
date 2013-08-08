<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/classes/users.php';
$users = new users();
$users->logout();
header('location: /');
?>