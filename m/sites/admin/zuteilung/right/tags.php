<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/comps/mysqlconnect.php';

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

while ($row = mysql_fetch_assoc($query)) {
	$studentCounter++;
	$x++;
	if ($i == 0) {
		$kategorie[$i]['plz'] = $row['plz'];
		$kategorie[$i]['ort'] = $row['ort'];
		$i++;
		$studentCounter = 0;
	} else if ($kategorie[$i-1]['plz'] != $row['plz']) {
		$kategorie[$i]['plz'] = $row['plz'];
		$kategorie[$i]['ort'] = $row['ort'];
		// $location[$i] = $row['plz'].' '.$row['ort'].' ('.$openCounter.')';
		
		echo '<a href="/zuteilung/__/', $kategorie[$i-1]['plz'], '" class="filtertag right', (in_array($kategorie[$i-1]['plz'], $sponsorPlz) && in_array($kategorie[$i-1]['ort'], $sponsorOrt)) ? ' opponent' : '', '" data-locationplz="', $kategorie[$i-1]['plz'], '" data-locationort="', $kategorie[$i-1]['ort'], '">', $kategorie[$i-1]['plz'], ' ', $kategorie[$i-1]['ort'], ' <span class="open">', $studentCounter, '</span></a>';
		
		$i++;
		$studentCounter = 0;
	}
	if ($x == mysql_num_rows($query)) {
		echo '<a href="/zuteilung/__/', $row['plz'], '" class="filtertag right', (in_array($row['plz'], $sponsorPlz) && in_array($row['ort'], $sponsorOrt)) ? ' opponent' : '', '" data-locationplz="', $row['plz'], '" data-locationort="', $row['ort'], '">', $row['plz'], ' ', $row['ort'], ' <span class="open">', $studentCounter+1, '</span></a>';
	}
}

?>