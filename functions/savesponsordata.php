<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/comps/mysqlconnect.php';
$id = $_POST['id'];
$sponsern = ($_POST['sponsern'] == 'true') ? '1' : '0';
$ballheft = ($_POST['ballheft'] == 'true') ? '1' : '0';
$flyer = ($_POST['flyer'] == 'true') ? '1' : '0';
$verlinkung = ($_POST['verlinkung'] == 'true') ? '1' : '0';
$sachpreise = ($_POST['sachpreise'] == 'true') ? '1' : '0';
$sachpreis = ($_POST['sachpreise'] != '') ? explode('<#>', $_POST['sachpreis']) : '';
$spende = ($_POST['spende'] == 'true') ? '1' : '0';
$spendenbetrag = $_POST['spendenbetrag'];
$format = $_POST['format'];
$betrag = $_POST['betrag'];

if ($sachpreise == '1') {
	foreach ($sachpreis as $key => $value) {
		$preis = explode(';', $value);
		$anzahl = $preis[0];
		$name = $preis[1];
		mysql_query("INSERT INTO sachpreise SET anzahl = '$anzahl', name = '$name', sponsorid = '$id'");
	}
}

mysql_query("UPDATE sponsoren SET sponsern = '$sponsern', ballheft = '$ballheft', flyer = '$flyer', verlinkung = '$verlinkung', sachpreise = '$sachpreise', spende = '$spende', spendenbetrag = '$spendenbetrag', format_im_ballheft = '$format', betrag = '$betrag', fertig = '1' WHERE `id`=$id");

echo 'Daten wurden abgespeichert.';

?>