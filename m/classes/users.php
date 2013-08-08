<?php

require 'mysql.php';

class users{

	function validate_user($un, $pwd){
	
		$mysql = new mysql();
		
		$ensure_credentials = $mysql->login($un, md5($pwd));
		
		if($ensure_credentials){
		
			$_SESSION['status'] = 'angemeldet';
			$_SESSION['name'] = $_POST['name'];
			$_SESSION['keepin'] = $_POST['keepin'];
			
			$mysql->getData($_SESSION['name']);
			
			if(isset($_SESSION['ref'])){
				header('location: '.$_SESSION['ref']);
			}
			else{
				header('location: /');
			}
			
		}
		else return "Bitte geben Sie einen korrekten Namen und ein korrektes Passwort ein.";
	
	}
	
	function logout(){
		
		if(isset($_SESSION['status'])){
		
			unset($_SESSION['status']);
			unset($_SESSION['userid']);
			unset($_SESSION['name']);
			unset($_SESSION['mail']);
			unset($_SESSION['avatar']);
			unset($_SESSION['kernteam']);
			unset($_SESSION['admin']);
			unset($_SESSION['userart']);
			unset($_SESSION['ref']);
		
			if(isset($_COOKIE[session_name()])){
			
				setcookie(session_name(), '', time() - 1000);
				session_destroy();
			
			}
		
		}
		
	}
	
	function userconfirm(){
	
		session_start();
		if($_SESSION['status'] != 'angemeldet') header('location: /');
	
	}

}

?>