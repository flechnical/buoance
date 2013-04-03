<?php

header('Access-Control-Allow-Origin: *');

// $conn = mysql_connect('mysql.lima-city.de', 'USER259592', '8w3C7Kqy');
// mysql_select_db('db_259592_1');

$userid = $_POST['userid'];
$name = $_POST['name'];

if (!file_exists('../friends/'.$userid.'.txt'))
{
	$file = fopen('../friends/'.$userid.'.txt', 'a');
	fclose($file);
}

// $friends = mysql_result(mysql_query("SELECT friendsids FROM friends WHERE username = '$name'"), 0);
$current10 = time()-10;

if (filesize('../friends/'.$userid.'.txt') != 0)
{
	$friends = explode('(:]', file_get_contents('../friends/'.$userid.'.txt'));
	
	if (!file_exists('../'.$userid.'.txt'))
	{
		$file = fopen('../'.$userid.'.txt', 'a');
		fclose($file);
	}
	$counter = file('../'.$userid.'.txt');
	
	// $query = mysql_query("SELECT * FROM users WHERE id IN ($friends)");
	
	foreach ($friends as $friend)
	{
		list($user['id'], $user['username']) = explode('[->)', $friend);
		$counts = 0;
		foreach ($counter as $count)
		{
			$count = explode(': ', $count);
			if ($count[0] == $user['username'])
			{
				$counts = $count[1];
			}
		}
		if (file_exists('../lastactive/'.$user['id'].'.txt'))
		{
			$lastactive = file_get_contents('../lastactive/'.$user['id'].'.txt');
		}
		else
		{
			$lastactive = '1234567890';
		}
		echo '<div class="user ', $user['username'], ($counts > 0) ? ' new' : '', '" onclick="openChat(', $user['id'], ', \'', $user['username'], '\')">', $user['username'], ($counts > 0) ? '<div>'.$counts.'</div>' : '', '<img src="/pics/', ((int)$lastactive >= $current10) ? 'green' : 'grey', '.png" alt="', ((int)$lastactive >= $current10) ? 'on' : 'off', '" /></div>';
	}
}
else
{
	echo 'leer';
}

// mysql_close($conn);

?>