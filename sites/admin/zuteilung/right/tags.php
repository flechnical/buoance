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

$query = mysql_query("SELECT plz, ort FROM students ORDER BY plz ASC");
$i = 0;
$studentCounter = 0;
$x = 0;

// alt

// while ($row = mysql_fetch_assoc($query)) {
	// $studentCounter++;
	// if (($i == 0) || ($location[$i-1] != $row['plz'])) {
		// $location[$i] = $row['plz'];
		
		// // $location[$i] = $row['plz'].' '.$row['ort'].' ('.$openCounter.')';
		
		// //echo '<div class="filtertag" data-location="', $row['plz'], '">', $row['plz'], ' ', $row['ort'], ' (', $studentCounter, ')</div>'; // einfach Button mit "Link der aktuellen Seite bekommen" erstellen/im JavaScript umaendern, wenn Filter bei Schueler gesetzt wurde -> dynamisch aendern, einfach aufnehmen
		
		// $i++;
		// $studentCounter = 0;
	// }
// }

while ($row = mysql_fetch_assoc($query)) {
	$studentCounter++;
	$x++;
	if ($i == 0) {
		$kategorie[$i]['plz'] = $row['plz'];
		$kategorie[$i]['ort'] = $row['ort'];
		$i++;
		$studentCounter = 0;
	} else if ($kategorie[$i-1]['plz'] != $row['plz'] || $kategorie[$i-1]['ort'] != $row['ort']) {
		$kategorie[$i]['plz'] = $row['plz'];
		$kategorie[$i]['ort'] = $row['ort'];
		// $location[$i] = $row['plz'].' '.$row['ort'].' ('.$openCounter.')';
		
		echo '<a href="/zuteilung/__/', $kategorie[$i-1]['plz'], '" class="filtertag right', (in_array($kategorie[$i-1]['plz'], $sponsorPlz) && in_array($kategorie[$i-1]['ort'], $sponsorOrt)) ? ' opponent' : '', '" data-locationplz="', $kategorie[$i-1]['plz'], '" data-locationort="', $kategorie[$i-1]['ort'], '">', $kategorie[$i-1]['plz'], ' ', $kategorie[$i-1]['ort'], ' (', $studentCounter, ')</a>'; // einfach Button mit "Link der aktuellen Seite bekommen" erstellen/im JavaScript umaendern, wenn Filter bei Schueler gesetzt wurde -> dynamisch aendern, einfach aufnehmen
		
		$i++;
		$studentCounter = 0;
	}
	if ($x == mysql_num_rows($query)) {
		echo '<a href="/zuteilung/__/', $row['plz'], '" class="filtertag right', (in_array($row['plz'], $sponsorPlz) && in_array($row['ort'], $sponsorOrt)) ? ' opponent' : '', '" data-locationplz="', $row['plz'], '" data-locationort="', $row['ort'], '">', $row['plz'], ' ', $row['ort'], ' (', $studentCounter+1, ')</a>';
	}
}

mysql_close($mysql);
?>