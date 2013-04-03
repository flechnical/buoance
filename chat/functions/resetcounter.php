<?php

header('Access-Control-Allow-Origin: *');

// Loescht die Counter-Zeile des aktuellen Chat-Partners.
$eigeneid = $_POST['userid'];
$partnerid = $_POST['partnerid'];
$partnername = $_POST['partnername'];

// Wenn die eigene Counter-Datei noch nicht existiert, wird sie erstellt.
if (!file_exists('../'.$eigeneid.'.txt'))
{
	$file = fopen('../'.$eigeneid.'.txt', 'a');
	fclose($file);
}

$counter = file('../'.$eigeneid.'.txt');
$protokoll = fopen('../'.$eigeneid.'.txt', 'w+');
foreach ($counter as $count)
{
	$count = explode(': ', $count);
	if ($count[0] != $partnername)
	{
		fwrite($protokoll, $count[0].': '.$count[1]);
	}
}
fclose($protokoll);

?>