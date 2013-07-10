<div id="resizeback"></div>
<section id="left" class="clearfix nano">
	<div class="content">
		<h2>Sponsoren</h2>
		<ul>
			<?php
				// erst die Suche dann soll reingeladen werden
				// Suche mit Vorschlägen wenn Eingabe mögliche Orte anzeigen
				
				
				
				// require_once $_SERVER['DOCUMENT_ROOT'].'/comps/constants.php';
			
				// $mysql = mysql_connect(dbserver, dbuser, dbpass)
				// or die ("Es konnte keine Verbindung zu MySQL hergestellt werden.");

				// mysql_select_db(db1)
				// or die ("Es konnte keine Verbindung zur Datenbank hergestellt werden.");
				
				// mysql_query("SET NAMES 'utf8'");
				// $sponsoren = mysql_query("SELECT * FROM sponsoren WHERE student IS NULL OR student = '' ORDER BY `plz` ASC, `firmenname` ASC") or die(mysql_error());
				
				// while ($sponsor = mysql_fetch_assoc($sponsoren)) {
					// echo '<li draggable="true" class="listitem" data-index="', $sponsor['id'], '">', $sponsor['firmenname'], ' - ', $sponsor['strasse'], ' - ', $sponsor['plz'], ' - ', $sponsor['ort'], '</li>';
				// }
			?>
		</ul>
	</div>
</section>
<div id="middle"><div></div></div>
<section id="right" class="clearfix nano">
	<div class="content">
		<h2>Schüler</h2>
		<?php
			// gleich wie oben
			
			
			// $students = mysql_query("SELECT * FROM students ORDER BY `plz` ASC, `klasse` ASC, `nachname` ASC") or die(mysql_error());
			
			// while ($student = mysql_fetch_assoc($students)) {
				// $id = $student['id'];
				// $firmen = mysql_query("SELECT * FROM sponsoren WHERE student = $id ORDER BY plz ASC, firmenname ASC") or die(mysql_error());
				// echo '<div class="dropperContainer', (mysql_num_rows($firmen) != 0) ? ' dropped' : '', '"><div class="itemDropper" data-index="', $student['id'], '"><div class="student"><h3>', $student['nachname'], ' ', $student['vorname'];
				// if (mysql_num_rows($firmen) != 0) {
					// echo '<div>', mysql_num_rows($firmen), '</div>';
				// }
				// echo '</h3><img src="', ($student['avatar'] == '0') ? '/img/user' : 'http://dl.dropbox.com/u/21062820/avatar/'.$student['id'], '.png" alt="Avatar" /></div>';
				// if (mysql_num_rows($firmen) != 0) {
					// echo '<div class="firms"><ul>';
				// }
				// while ($firma = mysql_fetch_assoc($firmen)) {
					// echo '<li draggable="true" class="listitem" data-index="', $firma['id'], '">', $firma['firmenname'], ' - ', $firma['strasse'], ' - ', $firma['plz'], ' - ', $firma['ort'], '</li>';
				// }
				// if (mysql_num_rows($firmen) != 0) {
					// echo '</ul></div>';
				// }
				// echo '</div></div>';
			// }
			
			// mysql_close($mysql);
		?>
	</div>
</section><!-- wenn included, dann werden die Sachen unten nicht eingefuegt -->