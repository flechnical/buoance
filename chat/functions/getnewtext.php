<?php

header('Access-Control-Allow-Origin: *');

$eigeneid = $_POST['myid'];
$partnerid = $_POST['partnerid'];
$partnername = $_POST['partnername'];

if ($eigeneid < $partnerid)
{
	$id1 = $eigeneid;
	$id2 = $partnerid;
}
else
{
	$id1 = $partnerid;
	$id2 = $eigeneid;
}

// Schaut ob die Counterdatei des Partners da ist und erstellt sie.
if (!file_exists('../'.$eigeneid.'.txt'))
{
	$file = fopen('../'.$eigeneid.'.txt', 'a');
	fclose($file);
}

// Oeffnet die Counterdatei des Partners und nimmt den Wert beim eigenen Usernamen.
$chatpartner = file('../'.$eigeneid.'.txt');
$newtexts = 0;
foreach ($chatpartner as $partner)
{
	$partner = explode(': ', $partner);
	if ($partner[0] == $partnername)
	{
		$newtexts = $partner[1];
	}
}

if (!file_exists('../'.$id1.'-'.$id2.'.txt'))
{
	$file = fopen('../'.$id1.'-'.$id2.'.txt', 'a');
	fclose($file);
}

$readfile = file('../'.$id1.'-'.$id2.'.txt');
$total = count($readfile) - $newtexts;
foreach ($readfile as $key => $zeile)
{
	if ($key >= $total)
	{
		$nachricht = explode('[->)', $zeile);
		$time = date('H:i d.m.Y', (int)$nachricht[2]);
		echo '<div class="text new" title="', $time, '"><div title="', $partnername, '" class="profile"></div>', $nachricht[1], '</div>';
	}
}

?>