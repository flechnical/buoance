<?php
session_start();
require_once 'classes/users.php';
$users = new users();
$users->logout();
?>