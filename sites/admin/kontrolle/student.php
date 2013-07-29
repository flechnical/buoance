<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/comps/mysqlconnect.php';

$student = $_GET['student'];
$query = mysql_query("SELECT * FROM sponsoren WHERE `student` = '$student'");

while ($row = mysql_fetch_assoc($query)) {
	$id = $row['id'];
	$name = $row['firmenname'];
	$sponsern = $row['sponsern'];
	$fertig = $row['fertig'];
	$abgeschlossen = $row['abgeschlossen'];
	$notes = $row['notizen'];
	if ($notes) {
		$notesicon = <<<HERE
			<img src="/img/note.png" class="comment" />
HERE;
	} else {
		$notesicon = '';
	}
	if ($fertig) {
		$jaselected = ($sponsern) ? ' selected' : '';
		$neinselected = (!$sponsern) ? ' selected' : '';
		$sponsern = <<<HERE
			<label for="sponsern$id">Sponsern </label>
			<select id="sponsern$id" name="sponsern$id">
				<option value="1"$jaselected>Ja</option>
				<option value="0"$neinselected>Nein</option>
			</select>
HERE;
		$fertigicon = <<<HERE
			<img src="/img/tick.png" class="confirm" />
HERE;
		$fertig = <<<HERE
			<div class="confirmdata">$sponsern</div>
HERE;
	} else {
		$fertigicon = '';
		$fertig = '';
	}
	if ($notes) {
		$notes = <<<HERE
			<div class="notes">$notes</div>
HERE;
	} else {
		$notes = '';
	}
	echo <<<HERE
		<li>
			<span>$name</span>
			<div class="panel">$notesicon$fertigicon</div>
			<div class="info">$fertig$notes</div>
		</li>
HERE;
}

?>