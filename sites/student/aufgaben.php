<section id="main" class="nano">
	<div class="content">
		<h2>Aufgaben</h2>
		<?php
		
		$userid = $_SESSION['userid'];
		
		require_once $_SERVER['DOCUMENT_ROOT'].'/comps/constants.php';
		
		$mysql = mysql_connect(dbserver, dbuser, dbpass)
		or die ("Es konnte keine Verbindung zu MySQL hergestellt werden.");
		
		mysql_select_db(db1)
		or die ("Es konnte keine Verbindung zur Datenbank hergestellt werden.");
		
		mysql_query("SET NAMES 'utf8'");
		
		$query = mysql_query("SELECT * FROM aufgaben WHERE `student`='$userid'"); // ORDER BY nach Erstellungs- oder Fertigstellungsdatum sortieren (oder beides)
		
		while ($row = mysql_fetch_assoc($query)) {
			echo $row['title'];
		}
		
		?>
	</div>
</section>