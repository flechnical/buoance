<?php

session_start();

if (isset($_SESSION['userid']))
{
	echo 'userid: ', $_SESSION['userid'], ';name: ', $_SESSION['name'], ';';
}
else
{
	echo 'false';
}
	
?>