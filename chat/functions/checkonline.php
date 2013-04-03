<?php

header('Access-Control-Allow-Origin: *');

$name = $_POST['name'];
$posterid = $_POST['id'];
if (file_exists('../lastactive/'.$posterid.'.txt'))
{
	$lastactive = file_get_contents('../lastactive/'.$posterid.'.txt');
}
else
{
	$lastactive = '1234567890';
}
$current = time();

if ((int)$lastactive + 10 >= $current)
{
	echo 'online';
}
else
{
	echo 'offline';
}

?>