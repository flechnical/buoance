<?php

$inhalt = scandir('../');
unset($inhalt[0], $inhalt[1]);

foreach($inhalt as $objekt)
{	
	$objekt = '../'.$objekt;
	if (filetype($objekt) != 'dir')
	{
		unlink($objekt);
	}
}

?>