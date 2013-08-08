<?php
set_time_limit(0);
require 'DropboxUploader.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/comps/mysqlconnect.php';
try {
	if ($_FILES['file']['error'] !== UPLOAD_ERR_OK)
		throw new Exception('File was not successfully uploaded from your computer.');
		
	if ($_FILES['file']['name'] === "")
		throw new Exception('File name not supplied by the browser.');

	// Upload
	$uploader = new DropboxUploader('buoance@outlook.com', '1e/W*k4E'); // E-Mail und Passwort fuer Account
	$uploader->upload($_FILES['file']['tmp_name'], 'Public/sponsoring/'.$_POST['directory'],  $_POST['filename'].'.pdf'); // Ordner = Schuelernummer und Dokument = Unternehmensnummer
	$id = $_POST['filename'];
	
	mysql_query("UPDATE sponsoren SET `dokument` = '1' WHERE id = '$id'");
} catch(Exception $e) {
	echo 'Error: ' . htmlspecialchars($e->getMessage());
}
?>