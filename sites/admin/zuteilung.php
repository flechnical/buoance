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

if (isset($_GET['sponsoren']) && $_GET['sponsoren'] != '__') {
	$key1 = array_search($_GET['sponsoren'], $sponsorPlz);
	$sponsorenOrt = $sponsorOrt[$key1];
	$sponsorenLocation = $_GET['sponsoren'].' '.$sponsorOrt[$key1];
	echo '<script type="text/javascript">sponsorLocation = ', $_GET['sponsoren'], '</script>';
} else {
	$sponsorenLocation = false;
	echo '<script type="text/javascript">sponsorLocation = \'__\'</script>';
}

if (isset($_GET['students']) && $_GET['students'] != '__') {
	$key2 = array_search($_GET['students'], $studentPlz);
	$studentenOrt = $studentOrt[$key2];
	$studentsLocation = $_GET['students'].' '.$studentOrt[$key2];
	echo '<script type="text/javascript">studentLocation = ', $_GET['students'], '</script>';
} else {
	$studentsLocation = false;
	echo '<script type="text/javascript">studentLocation = \'__\'</script>';
}

?>
<div id="resizeback"></div>
<section id="left" class="clearfix nano">
	<div class="content">
		<h2>Sponsoren</h2>
		<div style="<?php if ($sponsorenLocation) echo 'display: block;'; ?>" class="filter">
			<span><?php echo $sponsorenLocation; ?></span> X
		</div>
		<div class="<?php echo ($sponsorenLocation) ? 'listcontainer' : 'tagwrapper'; ?>">
			<?php
				
				if ($sponsorenLocation) {
					$sponsorenPlz = $_GET['sponsoren'];
					$sponsoren = mysql_query("SELECT * FROM sponsoren WHERE student IS NULL AND plz = '$sponsorenPlz' AND ort = '$sponsorenOrt' ORDER BY `plz` ASC, `firmenname` ASC") or die(mysql_error());
					
					while ($sponsor = mysql_fetch_assoc($sponsoren)) {
						echo '<li draggable="true" class="listitem" data-index="', $sponsor['id'], '">', $sponsor['firmenname'], '</li>';
					}
				} else {
					$query = mysql_query("SELECT plz, ort, student FROM sponsoren WHERE student IS NULL ORDER BY plz ASC, ort ASC");
					$i = 0;
					$openCounter = 0;
					$x = 0;
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
							
							echo '<a href="/zuteilung/', $kategorie[$i-1]['plz'], '/__" class="filtertag left', (in_array($kategorie[$i-1]['plz'], $studentPlz) && in_array($kategorie[$i-1]['ort'], $studentOrt)) ? ' opponent' : '', '" data-locationplz="', $kategorie[$i-1]['plz'], '" data-locationort="', $kategorie[$i-1]['ort'], '">', $kategorie[$i-1]['plz'], ' ', $kategorie[$i-1]['ort'], ' (', $openCounter, ')</a>'; // einfach Button mit "Link der aktuellen Seite bekommen" erstellen/im JavaScript umaendern, wenn Filter bei Schueler gesetzt wurde -> dynamisch aendern, einfach aufnehmen
							
							$i++;
							$openCounter = 0;
						}
						if ($x == mysql_num_rows($query)) {
							echo '<a href="/zuteilung/', $row['plz'], '/__" class="filtertag left', (in_array($row['plz'], $studentPlz) && in_array($row['ort'], $studentOrt)) ? ' opponent' : '', '" data-locationplz="', $row['plz'], '" data-locationort="', $row['ort'], '">', $row['plz'], ' ', $row['ort'], ' (', $openCounter+1, ')</a>';
						}
					}
				}
				
			?>
		</div>
	</div>
</section>
<div id="middle"><div></div></div>
<section id="right" class="clearfix nano">
	<div class="content">
		<h2>Schüler</h2>
		<div style="<?php if ($studentsLocation) echo 'display: block;'; ?>" class="filter">
			<span><?php echo $studentsLocation; ?></span> X
		</div>
		<div class="<?php echo ($studentsLocation) ? 'listcontainer' : 'tagwrapper'; ?>">
		<?php
			
				if ($studentsLocation) {
					$studentenPlz = $_GET['students'];
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
				} else {
					$query = mysql_query("SELECT plz, ort FROM students ORDER BY plz ASC");
					$i = 0;
					$x = 0;
					$studentCounter = 0;

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
				}
			
			mysql_close($mysql);
		?>
		</div>
	</div>
</section><!-- wenn included, dann werden die Sachen unten nicht eingefuegt -->