<?php

$sponsorPlz = ($_GET['sponsoren'] != '__') ? $_GET['sponsoren'] : false;
$studentNum = ($_GET['students'] != '__') ? $_GET['students'] : false;

require_once $_SERVER['DOCUMENT_ROOT'].'/comps/mysqlconnect.php';

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

?>