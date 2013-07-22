<?php

$result = mysql_query("SELECT * FROM sponsoren WHERE firmenname LIKE '%$search%' OR strasse LIKE '%$search%'");

while ($row = mysql_fetch_assoc($result)) {
$firmenname = $row['firmenname'];
$strasse = $row['strasse'];
$plz = $row['plz'];
$ort = $row['ort'];
$student = ($row['student']) ? $row['student'] : '-';
echo <<<HERE
<li><span class="dname">$firmenname</span><span class="dstreet">$strasse</span><span class="dplz">$plz $ort</span><span class="dstudent">$student</span></li>
HERE;
}

?>

wenn das von AJAX abgerufen wird, die gefilterten Sponsoren ausgeben / bei searchsponsors.php nachschauen