<?php

$search = $_POST['term'];

require_once $_SERVER['DOCUMENT_ROOT'].'/comps/constants.php';

$mysql = mysql_connect(dbserver, dbuser, dbpass)
or die ("Es konnte keine Verbindung zu MySQL hergestellt werden.");

mysql_select_db(db1)
or die ("Es konnte keine Verbindung zur Datenbank hergestellt werden.");

mysql_query("SET NAMES 'utf8'");

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

if (mysql_num_rows($result) == 0) {
	$names = mysql_query("SELECT firmenname FROM sponsoren ORDER BY firmenname ASC");
	$streets = mysql_query("SELECT strasse FROM sponsoren ORDER BY strasse ASC");

	$vorschlag = array(); // vllt. bringt das was oder ist egal ??
	$i = 0;

	while ($row = mysql_fetch_array($names)) {
		if (!in_array($row[0], $vorschlag)) {
			$vorschlag[$i] = $row[0];
			$temparr = explode(' ', $row[0]);
			if (count($temparr) != 1) {
				foreach ($temparr as $part) {
					if (!in_array($part, $vorschlag)) { // damit GmbH nicht oefter vorkommt
						$i++;
						$vorschlag[$i] = $part;
					}
				}
			}
			$i++;
		}
	}
	while ($row = mysql_fetch_array($streets)) {
		if (!in_array($row[0], $vorschlag)) {
			$vorschlag[$i] = $row[0];
			$temparr = explode(' ', $row[0]);
			if (count($temparr) != 1) {
				foreach ($temparr as $part) {
					if (!in_array($part, $vorschlag)) { // damit GmbH nicht oefter vorkommt
						$i++;
						$vorschlag[$i] = $part;
					}
				}
			}
			$i++;
		}
	}
	$percent = array();
	$x = 0;
	while (list($key, $str) = each($vorschlag)) {
		similar_text($str, $search, $prozent);
		$percent[$x] = $prozent;
		$x++;
	}
	arsort($percent);
	// $maxp = max($percent);
	// $indexp = array_search($maxp, $percent);
	echo '<li class="tags">Es wurden keine Suchergebnisse gefunden.</li>';
	echo '<li class="tags">Meinten Sie: ';
	$k = 0;
	foreach ($percent as $key => $value) {
		if ($k < 2) {
			echo '<span class="suggestion" onclick="setSearch(this.innerHTML);">', $vorschlag[$key], '</span>', ($k != 1) ? ' | ' : '';
		} else {
			break;
		}
		$k++;
	}
	echo '</li>';
}

?>