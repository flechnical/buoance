<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/comps/constants.php';

$mysql = mysql_connect(dbserver, dbuser, dbpass)
or die ("Es konnte keine Verbindung zu MySQL hergestellt werden.");

mysql_select_db(db1)
or die ("Es konnte keine Verbindung zur Datenbank hergestellt werden.");

mysql_query("SET NAMES 'utf8'");

$query = mysql_query("SELECT plz, ort, student FROM sponsoren WHERE student IS NULL ORDER BY plz ASC, ort ASC");
$i = 0;
while ($row = mysql_fetch_assoc($query)) {
	if (($i == 0) || ($sponsorPlz[$i-1] != $row['plz'] || $sponsorOrt[$i-1] != $row['ort'])) {
		$sponsorPlz[$i] = $row['plz'];
		$sponsorOrt[$i] = $row['ort'];
		$i++;
	}
}

$query = mysql_query("SELECT plz, ort FROM students ORDER BY plz ASC");
$i = 0;
while ($row = mysql_fetch_assoc($query)) {
	if (($i == 0) || ($studentPlz[$i-1] != $row['plz'] || $studentOrt[$i-1] != $row['ort'])) {
		$studentPlz[$i] = $row['plz'];
		$studentOrt[$i] = $row['ort'];
		$i++;
	}
}

$studentenPlz = $_GET['plz'];
$studentenOrt = $_GET['ort'];
$students = mysql_query("SELECT * FROM students WHERE plz = '$studentenPlz' AND ort = '$studentenOrt' ORDER BY `plz` ASC, `klasse` ASC, `nachname` ASC") or die(mysql_error());

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

mysql_close($mysql);
?>