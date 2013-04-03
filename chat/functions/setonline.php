<?php

header('Access-Control-Allow-Origin: *');

$lastactive = fopen('../lastactive/'.$_POST['userid'].'.txt', 'w');
fwrite($lastactive, time());
fclose($lastactive);

?>