<li class="tags"><span class="dname"></span><span class="dstreet"></span><span class="dplz"><?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/comps/constants.php';

	$mysql = mysql_connect(dbserver, dbuser, dbpass)
	or die ("Es konnte keine Verbindung zu MySQL hergestellt werden.");

	mysql_select_db(db1)
	or die ("Es konnte keine Verbindung zur Datenbank hergestellt werden.");

	mysql_query("SET NAMES 'utf8'");

	$query = mysql_query("SELECT plz, ort, student FROM sponsoren ORDER BY plz ASC, ort ASC");
	$i = 0;
	$openCounter = 0;
	while ($row = mysql_fetch_assoc($query)) {
		$openCounter++;
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
			
			echo '<a href="/bearbeitung/', $kategorie[$i-1]['plz'], '/__" class="filtertag" data-locationplz="', $kategorie[$i-1]['plz'], '" data-locationort="', $kategorie[$i-1]['ort'], '">', $kategorie[$i-1]['plz'], ' ', $kategorie[$i-1]['ort'], ' (', $openCounter, ')</a>'; // einfach Button mit "Link der aktuellen Seite bekommen" erstellen/im JavaScript umaendern, wenn Filter bei Schueler gesetzt wurde -> dynamisch aendern, einfach aufnehmen
			
			$i++;
			$openCounter = 0;
		}
	}

?></span><span class="dstudent"><?php

	$query = mysql_query("SELECT student FROM sponsoren WHERE student IS NOT NULL ORDER BY student ASC");
	$i = 0;
	$x = 0;
	$studentCounter = 0;

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
			
			echo '<a href="/bearbeitung/__/', $student['vorname'], $student['nachname'], '" class="filtertag" >', $student['vorname'], ' ', $student['nachname'], ' (', $studentCounter, ')</a>'; // einfach Button mit "Link der aktuellen Seite bekommen" erstellen/im JavaScript umaendern, wenn Filter bei Schueler gesetzt wurde -> dynamisch aendern, einfach aufnehmen
			
			$i++;
			$studentCounter = 0;
		}
		if ($x == mysql_num_rows($query)) {
			$studentid = $row['student'];
			$student = mysql_fetch_assoc(mysql_query("SELECT vorname, nachname FROM students WHERE `id`='$studentid' LIMIT 1"));
			echo '<a href="/bearbeitung/__/', $student['vorname'], $student['nachname'], '" class="filtertag" >', $student['vorname'], ' ', $student['nachname'], ' (', $studentCounter+1, ')</a>';
		}
	}

	mysql_close($mysql);
?></span></li>