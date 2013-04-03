<?php

header('Access-Control-Allow-Origin: *');

function delnl($text)
{
	return preg_replace("#\r|\n#", '', $text);
}

if (isset($_POST['text']))
{
	$eigeneid = $_POST['userid'];
	$name = $_POST['name'];
	$partnerid = $_POST['partnerid'];
	
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

	// Speichert den abgesendeten Text in die Textdatei ab.
	$text = stripslashes($_POST['text']);
	$text = htmlspecialchars($text);
	$text = nl2br($text);
	$text = delnl($text);
	$time = time();
	$string = $eigeneid.'[->)'.$text.'[->)'.$time."\r\n";
	$writefile = fopen('../'.$id1.'-'.$id2.'.txt', 'a');
	fwrite($writefile, $string);
	fclose($writefile);
	
	// Schaut ob die Counterdatei des Partners da ist und erstellt sie.
	if (!file_exists('../'.$partnerid.'.txt'))
	{
		$file = fopen('../'.$partnerid.'.txt', 'a');
		fclose($file);
	}
	
	// Oeffnet die Counterdatei des Partners und erhoeht den Wert um 1.
	$chatpartner = file('../'.$partnerid.'.txt');
	$count = fopen('../'.$partnerid.'.txt', 'w+');
	$bereitsda = false;
	foreach ($chatpartner as $partner)
	{
		$partner = explode(': ', $partner);
		if ($partner[0] == $name)
		{
			$partner[1] = $partner[1] + 1;
			$bereitsda = true;
		}
		fwrite($count, $partner[0].': '.(int)$partner[1]."\r\n");
	}
	if (!$bereitsda)
	{
		fwrite($count, $name.': 1'."\r\n");
	}
	fclose($count);
	
	$time = date('H:i d.m.Y', $time);
	echo '<div class="text right new" title="', $time, '"><div title="', $name, '" class="profile"></div>', $text, '</div>';
}

?>