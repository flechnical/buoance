<?php

$sponsorPlz = (isset($_GET['sponsorplz'])) ? $_GET['sponsorplz'] : false;
$studentNum = (isset($_GET['studentid'])) ? $_GET['studentid'] : false;

require_once $_SERVER['DOCUMENT_ROOT'].'/comps/constants.php';

$mysql = mysql_connect(dbserver, dbuser, dbpass)
or die ("Es konnte keine Verbindung zu MySQL hergestellt werden.");

mysql_select_db(db1)
or die ("Es konnte keine Verbindung zur Datenbank hergestellt werden.");

mysql_query("SET NAMES 'utf8'");

if ($sponsorPlz && !$studentNum) {
	$query = "SELECT * FROM sponsoren WHERE `plz` LIKE '%$sponsorPlz%'";
} else if (!$sponsorPlz && $studentNum) {
	$query = "SELECT * FROM sponsoren WHERE `student` LIKE '%$studentNum%'";
} else {
	$query = "SELECT * FROM sponsoren WHERE `plz` LIKE '%$sponsorPlz%' AND `student` LIKE '%$studentNum%'";
}
$result = mysql_query($query);

while ($row = mysql_fetch_assoc($result)) {
	$firmenname = $row['firmenname'];
	$strasse = $row['strasse'];
	$plz = $row['plz'];
	$ort = $row['ort'];
	$student = ($row['student']) ? $row['student'] : '-';
	echo "<li><span class=\"dname\">$firmenname</span><span class=\"dstreet\">$strasse</span><span class=\"dplz\">$plz $ort</span><span class=\"dstudent\">$student</span></li>";
}

mysql_close($mysql);

?>