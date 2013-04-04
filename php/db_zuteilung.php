<?php

require_once './comps/constants.php';

$student = $_POST['stud'];
$items = $_POST['items'];

$mysql = mysql_connect(dbserver, dbuser, dbpass)
or die ("Es konnte keine Verbindung zu MySQL hergestellt werden.");

mysql_select_db(db1)
or die ("Es konnte keine Verbindung zur Datenbank hergestellt werden.");

mysql_query("SET NAMES 'utf8'");
mysql_query("UPDATE sponsoren SET student = '$student' WHERE `id` IN($items)");

mysql_close($mysql);

echo $items;

?>