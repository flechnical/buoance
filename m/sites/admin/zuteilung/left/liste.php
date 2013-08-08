<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/comps/mysqlconnect.php';

$sponsorenPlz = $_GET['sponsoren'];
$sponsoren = mysql_query("SELECT * FROM sponsoren WHERE student IS NULL AND plz = '$sponsorenPlz' ORDER BY `plz` ASC, `firmenname` ASC") or die(mysql_error());

while ($sponsor = mysql_fetch_assoc($sponsoren)) {
	echo '<li draggable="true" class="listitem" data-index="', $sponsor['id'], '">', $sponsor['firmenname'], '</li>';
}

?>