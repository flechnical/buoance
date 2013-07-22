<?php
	session_start();
	$art = $_GET['art'];
	// vllt. noch pruefen ob auf aktiviertes geklickt wurde, dann wieder auf 'beides' stellen?
	$_SESSION['userart'] = $art;
	
	if(isset($_SESSION['ref'])){
		header('location: '.$_SESSION['ref']);
	}
	else{
		header('location: /');
	}
?>