<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/comps/constants.php';

class mysql{
	
	private $conn;
	
	function __construct(){
		$this->conn = new mysqli(dbserver, dbuser, dbpass, db1) or die('Ein Problem mit der Datenbank ist aufgetreten.');
	}
	
	function login($un, $pwd){
		
		$query = "SELECT * FROM students WHERE username = ? AND password = ? LIMIT 1";
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->bind_param('ss', $un, $pwd);
			$stmt->execute();
			
			if ($stmt->fetch()) {
				$stmt->close();
				return true;
			} else {
				return false;
			}
		
		}
	
	}
	
	function getData($username) {
		
		$stmt = $this->conn->prepare("SELECT id, mail, avatar, kernteam, admin FROM students WHERE username = ?");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		
		$stmt->bind_result($id, $mail, $avatar, $kernteam, $admin);
		
		while ($stmt->fetch()) {
			$_SESSION['userid'] = $id;
			$_SESSION['mail'] = $mail;
			$_SESSION['avatar'] = $avatar;
			$_SESSION['kernteam'] = $kernteam;
			$_SESSION['admin'] = $admin;
			if ($kernteam == '1' && $admin == '1') {
				$_SESSION['userart'] = 'beides';
			} else if ($kernteam == '1') {
				$_SESSION['userart'] = 'kernteam';
			} else if ($admin == '1') {
				$_SESSION['userart'] = 'admin';
			} else {
				$_SESSION['userart'] = 'student';
			}
		}
		
    $stmt->close();
	
	}

}

?>