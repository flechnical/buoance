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

if (isset($_GET['sponsoren']) && $_GET['sponsoren'] != '__') {
	$key1 = array_search($_GET['sponsoren'], $sponsorPlz);
	$sponsorenOrt = $sponsorOrt[$key1];
	$sponsor = $_GET['sponsoren'].' '.$sponsorOrt[$key1];
	echo '<script type="text/javascript">sponsorPlz = \'', $_GET['sponsoren'], '\';';
	echo 'sponsorOrt = \'', $sponsorenOrt, '\';</script>';
} else {
	$sponsor = false;
	echo '<script type="text/javascript">sponsorPlz = \'__\';sponsorOrt = \'__\';</script>';
}

if (isset($_GET['students']) && $_GET['students'] != '__') {
	$student = $_GET['students'];
	$studentData = mysql_fetch_assoc(mysql_query("SELECT vorname, nachname FROM students WHERE `id` = $student"));
	$studentFirst = $studentData['vorname'];
	$studentLast = $studentData['nachname'];
	echo '<script type="text/javascript">studentId = \'', $_GET['students'], '\';';
	echo 'studentFirst = \'', $studentFirst, '\';';
	echo 'studentLast = \'', $studentLast, '\';</script>';
} else {
	$student = false;
	echo '<script type="text/javascript">studentId = \'__\';studentFirst = \'__\';studentLast = \'__\';</script>';
}

?>
<section id="main" class="nano">
	<div class="content">
		<h2>Bearbeitung</h2>
		<div id="headings">
			<span class="hname"><input id="search" type="text" placeholder="Name oder Straße eingeben..." value="<?php echo (isset($_GET['search'])) ? $_GET['search'] : ''; ?>" /></span><span class="hplz">PLZ/Ort <div style="<?php if ($sponsor) echo 'display: block;'; ?>" class="filter"><span><?php echo $sponsor; ?></span> x</div></span><span class="hstudent">Schüler <div style="<?php if ($student) echo 'display: block;'; ?>" class="filter"><span><?php echo $studentFirst, ' ', $studentLast; ?></span> x</div></span>
		</div>
		<ul id="sponsorsearch">
		<?php
		if (!($sponsor || $student) && !isset($_GET['search'])) {
		?>
			<li class="tags"><span class="dname"></span><span class="dstreet"></span><span class="dplz"><?php

				$query = mysql_query("SELECT plz, ort, student FROM sponsoren ORDER BY plz ASC, ort ASC");
				$i = 0;
				$x = 0;
				$openCounter = 0;
				$kategorie = array(); // vllt. bringt das was oder ist egal ??
				while ($row = mysql_fetch_assoc($query)) {
					$openCounter++;
					$x++;
					if ($i == 0) {
						$kategorie[$i]['plz'] = $row['plz'];
						$kategorie[$i]['ort'] = $row['ort'];
						$kategorie[$i]['student'] = $row['student'];
						$i++;
						$openCounter = 0;
					} else if ($kategorie[$i-1]['plz'] != $row['plz'] || $kategorie[$i-1]['ort'] != $row['ort']) {
						$kategorie[$i]['plz'] = $row['plz'];
						$kategorie[$i]['ort'] = $row['ort'];
						$kategorie[$i]['student'] = $row['student'];
						// $location[$i] = $row['plz'].' '.$row['ort'].' ('.$openCounter.')';
						
						echo '<a href="/bearbeitung/', $kategorie[$i-1]['plz'], '/__" class="searchfilter" data-locationplz="', $kategorie[$i-1]['plz'], '" data-locationort="', $kategorie[$i-1]['ort'], '">', $kategorie[$i-1]['plz'], ' ', $kategorie[$i-1]['ort'], ' (', $openCounter, ')</a>'; // einfach Button mit "Link der aktuellen Seite bekommen" erstellen/im JavaScript umaendern, wenn Filter bei Schueler gesetzt wurde -> dynamisch aendern, einfach aufnehmen
						
						$i++;
						$openCounter = 0;
					}
					if ($x == mysql_num_rows($query)) {
						echo '<a href="/bearbeitung/', $row['plz'], '/__" class="searchfilter" data-locationplz="', $row['plz'], '" data-locationort="', $row['ort'], '">', $row['plz'], ' ', $row['ort'], ' (', $openCounter+1, ')</a>';
					}
				}

			?></span><span class="dstudent"><?php

				$query = mysql_query("SELECT student FROM sponsoren WHERE student IS NOT NULL ORDER BY student ASC");
				$i = 0;
				$x = 0;
				$studentCounter = 0;
				$kategorie = array(); // vllt. bringt das was oder ist egal ??
				while ($row = mysql_fetch_assoc($query)) {
					$studentCounter++;
					$x++;
					if ($i == 0) {
						$kategorie[$i]['student'] = $row['student'];
						$i++;
						$studentCounter = 0;
					} else if ($kategorie[$i-1]['student'] != $row['student']) {
						$kategorie[$i]['student'] = $row['student'];
						// $location[$i] = $row['plz'].' '.$row['ort'].' ('.$openCounter.')';
						
						$studentid = $kategorie[$i-1]['student'];
						$student = mysql_fetch_assoc(mysql_query("SELECT vorname, nachname FROM students WHERE `id`='$studentid' LIMIT 1"));
						
						echo '<a href="/bearbeitung/__/', $studentid, '" class="searchfilter" data-student="true" data-studentid="', $studentid, '" data-studentfirst="', $student['vorname'], '" data-studentlast="', $student['nachname'], '">', $student['vorname'], ' ', $student['nachname'], ' (', $studentCounter, ')</a>'; // einfach Button mit "Link der aktuellen Seite bekommen" erstellen/im JavaScript umaendern, wenn Filter bei Schueler gesetzt wurde -> dynamisch aendern, einfach aufnehmen
						
						$i++;
						$studentCounter = 0;
					}
					if ($x == mysql_num_rows($query)) {
						$studentid = $row['student'];
						$student = mysql_fetch_assoc(mysql_query("SELECT vorname, nachname FROM students WHERE `id`='$studentid' LIMIT 1"));
						echo '<a href="/bearbeitung/__/', $studentid, '" class="searchfilter" data-student="true" data-studentid="', $studentid, '" data-studentfirst="', $student['vorname'], '" data-studentlast="', $student['nachname'], '">', $student['vorname'], ' ', $student['nachname'], ' (', $studentCounter+1, ')</a>';
					}
				}
			?></span></li>
		<?php
		} else if (!isset($_GET['search'])) {
			$sponsorPlz = $_GET['sponsoren'];
			$studentNum = $_GET['students'];
			if ($sponsor && !$student) {
				$query = "SELECT * FROM sponsoren WHERE `plz` LIKE '%$sponsorPlz%'";
			} else if (!$sponsor && $student) {
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
		}
		mysql_close($mysql);
		?>
		</ul>
		<h2>Neuer Sponsor</h2>
	</div>
</section>