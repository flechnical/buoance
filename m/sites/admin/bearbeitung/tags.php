<li class="tags"><span class="dname"></span><span class="dstreet"></span><span class="dplz"><?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/comps/mysqlconnect.php';
	
	$query = mysql_query("SELECT plz, ort, student FROM sponsoren ORDER BY plz ASC, ort ASC");
	$i = 0;
	$x = 0;
	$openCounter = 0;
	$kategorie = array();
	while ($row = mysql_fetch_assoc($query)) {
		$openCounter++;
		$x++;
		if ($i == 0) {
			$kategorie[$i]['plz'] = $row['plz'];
			$kategorie[$i]['ort'] = $row['ort'];
			$kategorie[$i]['student'] = $row['student'];
			$i++;
			$openCounter = 0;
		} else if ($kategorie[$i-1]['plz'] != $row['plz']) {
			$kategorie[$i]['plz'] = $row['plz'];
			$kategorie[$i]['ort'] = $row['ort'];
			$kategorie[$i]['student'] = $row['student'];
			// $location[$i] = $row['plz'].' '.$row['ort'].' ('.$openCounter.')';
			
			echo '<a href="/bearbeitung/', $kategorie[$i-1]['plz'], '/__" class="searchfilter" title="', $kategorie[$i-1]['plz'], ' ', $kategorie[$i-1]['ort'], '" data-locationplz="', $kategorie[$i-1]['plz'], '" data-locationort="', $kategorie[$i-1]['ort'], '">', $kategorie[$i-1]['plz'], ' ', $kategorie[$i-1]['ort'], ' <span class="open">', $openCounter, '</span></a>';
			
			$i++;
			$openCounter = 0;
		}
		if ($x == mysql_num_rows($query)) {
			echo '<a href="/bearbeitung/', $row['plz'], '/__" class="searchfilter" title="', $row['plz'], ' ', $row['ort'], '" data-locationplz="', $row['plz'], '" data-locationort="', $row['ort'], '">', $row['plz'], ' ', $row['ort'], ' <span class="open">', $openCounter+1, '</span></a>';
		}
	}

?></span><span class="dstudent"><?php

	$query = mysql_query("SELECT student FROM sponsoren WHERE student IS NOT NULL ORDER BY student ASC");
	$i = 0;
	$x = 0;
	$studentCounter = 0;
	$kategorie = array();
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
			
			echo '<a href="/bearbeitung/__/', $studentid, '" class="searchfilter" data-student="true" data-studentid="', $studentid, '" data-studentfirst="', $student['vorname'], '" title="', $student['vorname'], ' ', $student['nachname'], '" data-studentlast="', $student['nachname'], '">', $student['vorname'], ' ', $student['nachname'], ' <span class="open">', $studentCounter, '</span></a>';
			
			$i++;
			$studentCounter = 0;
		}
		if ($x == mysql_num_rows($query)) {
			$studentid = $row['student'];
			$student = mysql_fetch_assoc(mysql_query("SELECT vorname, nachname FROM students WHERE `id`='$studentid' LIMIT 1"));
			echo '<a href="/bearbeitung/__/', $studentid, '" class="searchfilter" data-student="true" data-studentid="', $studentid, '" data-studentfirst="', $student['vorname'], '" title="', $student['vorname'], ' ', $student['nachname'], '" data-studentlast="', $student['nachname'], '">', $student['vorname'], ' ', $student['nachname'], ' <span class="open">', $studentCounter+1, '</span></a>';
		}
	}
?></span></li>