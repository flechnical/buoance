<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/comps/mysqlconnect.php';

$klasse = $_GET['klasse'];
$query = mysql_query("SELECT * FROM students WHERE `klasse` = '$klasse'");

while ($row = mysql_fetch_assoc($query)) {
	$id = $row['id'];
	$name = $row['vorname'].' '.$row['nachname'];
	echo <<<HERE
		<li><a href="/kontrolle/$klasse/$id" class="controllink">$name</a></li>
HERE;
}

?>