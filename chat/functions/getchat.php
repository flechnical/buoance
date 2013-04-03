<?php

header('Access-Control-Allow-Origin: *');

$eigeneid = $_POST['myid'];
$name = $_POST['myname'];
$partnerid = $_POST['id'];
$partnername = $_POST['name'];

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

if (!file_exists('../'.$id1.'-'.$id2.'.txt'))
{
	$file = fopen('../'.$id1.'-'.$id2.'.txt', 'a');
	fclose($file);
}
$text = file('../'.$id1.'-'.$id2.'.txt');
foreach ($text as $nachricht)
{
	$nachricht = explode('[->)', $nachricht);
	$time = date('H:i d.m.Y', (int)$nachricht[2]);
	echo '<div class="text', ($nachricht[0] == $eigeneid) ? ' right' : '', '" title="', $time, '"><div title="', ($nachricht[0] == $eigeneid) ? $name : $partnername, '" class="profile"></div>', $nachricht[1], '</div>';
}

?>