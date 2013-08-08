<?php

if (!isset($_SESSION)) {
	session_start();
}

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
		<div class="padding">
		<h2>Bearbeitung</h2>
		<div id="headings">
			<span class="hname"><input id="search" type="text" placeholder="Name oder Straße eingeben..." value="<?php echo (isset($_GET['search'])) ? $_GET['search'] : ''; ?>" /></span><span class="hplz<?php if ($sponsor) echo ' filter'; ?><?php if ($student && !$sponsor) echo ' nofilter'; ?>"><div class="textnode"<?php echo ($sponsor) ? ' title="'.$sponsor.'"' : ''; ?>><?php echo ($sponsor) ? $sponsor.'<span class="closefilter"></span>' : 'PLZ/Ort'; ?></div></span><span class="hstudent<?php if ($student) echo ' filter'; ?><?php if ($sponsor && !$student) echo ' nofilter'; ?>"><div class="textnode"<?php echo ($student) ? ' title="'.$studentFirst.' '.$studentLast.'"' : ''; ?>><?php echo ($student) ? $studentFirst.' '.$studentLast.'<span class="closefilter"></span>' : 'Schüler'; ?></div></span>
		</div>
		<ul id="sponsorsearch"<?php if (($sponsor || $student) || isset($_GET['search'])) echo ' class="notags"'; ?>>
		<?php
		if (!($sponsor || $student) && !isset($_GET['search'])) {
			include $_SERVER['DOCUMENT_ROOT'].'/sites/'.$_SESSION['userart'].'/bearbeitung/tags.php';
		} else if (!isset($_GET['search'])) {
			include $_SERVER['DOCUMENT_ROOT'].'/sites/'.$_SESSION['userart'].'/bearbeitung/liste.php';
		}
		?>
		</ul>
		Sponsoren:<br />
		löschen<br />
		hinzufügen<br />
		bearbeiten
		</div>
	</div>
</section>