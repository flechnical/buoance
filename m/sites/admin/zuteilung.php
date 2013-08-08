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
	$sponsorenLocation = $_GET['sponsoren'].'<br />'.$sponsorOrt[$key1];
	echo '<script type="text/javascript">sponsorLocation = \'', $_GET['sponsoren'], '\';</script>';
} else {
	$sponsorenLocation = false;
	echo '<script type="text/javascript">sponsorLocation = \'__\';</script>';
}

if (isset($_GET['students']) && $_GET['students'] != '__') {
	$key2 = array_search($_GET['students'], $studentPlz);
	$studentenOrt = $studentOrt[$key2];
	$studentsLocation = $_GET['students'].'<br />'.$studentOrt[$key2];
	echo '<script type="text/javascript">studentLocation = \'', $_GET['students'], '\';</script>';
} else {
	$studentsLocation = false;
	echo '<script type="text/javascript">studentLocation = \'__\';</script>';
}

?>
<div id="resizeback"></div>
<section id="left" class="clearfix nano">
	<div class="content">
		<div class="padding">
		<h2<?php if ($sponsorenLocation) echo ' class="filter"'; ?>><?php echo ($sponsorenLocation) ? $sponsorenLocation.'<span class="closefilter"></span>' : 'Open Sponsors'; ?></h2>
		<div class="<?php echo ($sponsorenLocation) ? 'listcontainer' : 'tagwrapper'; ?>">
			<?php
				if ($sponsorenLocation) {
					include $_SERVER['DOCUMENT_ROOT'].'/sites/'.$_SESSION['userart'].'/zuteilung/left/liste.php';
				} else {
					include $_SERVER['DOCUMENT_ROOT'].'/sites/'.$_SESSION['userart'].'/zuteilung/left/tags.php';
				}
			?>
		</div>
		</div>
	</div>
</section>
<div id="middle"><div></div></div>
<section id="right" class="clearfix nano">
	<div class="content">
		<div class="padding">
		<h2<?php if ($studentsLocation) echo ' class="filter"'; ?>><?php echo ($studentsLocation) ? $studentsLocation.'<span class="closefilter"></span>' : 'Students' ?></h2>
		<div class="<?php echo ($studentsLocation) ? 'listcontainer' : 'tagwrapper'; ?>">
		<?php
			if ($studentsLocation) {
				include $_SERVER['DOCUMENT_ROOT'].'/sites/'.$_SESSION['userart'].'/zuteilung/right/liste.php';
			} else {
				include $_SERVER['DOCUMENT_ROOT'].'/sites/'.$_SESSION['userart'].'/zuteilung/right/tags.php';
			}
		?>
		</div>
		Google Maps einbauen<br />
		Listen umstylen (einheitlicher)
		</div>
	</div>
</section><!-- wenn included, dann werden die Sachen unten nicht eingefuegt -->