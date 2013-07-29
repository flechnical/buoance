<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/comps/mysqlconnect.php';

$studentenPlz = $_GET['students'];
$students = mysql_query("SELECT * FROM students WHERE plz = '$studentenPlz' ORDER BY `plz` ASC, `klasse` ASC, `nachname` ASC") or die(mysql_error());

while ($student = mysql_fetch_assoc($students)) {
	$id = $student['id'];
	$firmen = mysql_query("SELECT * FROM sponsoren WHERE student = $id ORDER BY plz ASC, firmenname ASC") or die(mysql_error());
	echo '<div class="dropperContainer', (mysql_num_rows($firmen) != 0) ? ' dropped' : '', '"><div class="itemDropper" data-index="', $student['id'], '"><div class="student"><h3>', $student['nachname'], ' ', $student['vorname'];
	if (mysql_num_rows($firmen) != 0) {
		echo '<div>', mysql_num_rows($firmen), '</div>';
	}
	echo '</h3><img src="', ($student['avatar'] == '0') ? '/img/user' : 'http://dl.dropbox.com/u/21062820/avatar/'.$student['id'], '.png" alt="Avatar" /></div>';
	if (mysql_num_rows($firmen) != 0) {
		echo '<div class="firms"><ul>';
	}
	while ($firma = mysql_fetch_assoc($firmen)) {
		echo '<li draggable="true" class="listitem" data-index="', $firma['id'], '">', $firma['firmenname'], ' - ', $firma['strasse'], ' - ', $firma['plz'], ' - ', $firma['ort'], '</li>';
	}
	if (mysql_num_rows($firmen) != 0) {
		echo '</ul></div>';
	}
	echo '</div></div>';
}

?>