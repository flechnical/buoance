<section id="left" class="clearfix">
	<div class="scroll">
		<h2>Sponsoren</h2>
		<ul>
			<?php
				require_once '../comps/constants.php';
			
				$mysql = mysql_connect(dbserver, dbuser, dbpass)
				or die ("Es konnte keine Verbindung zu MySQL hergestellt werden.");

				mysql_select_db(db1)
				or die ("Es konnte keine Verbindung zur Datenbank hergestellt werden.");
				
				mysql_query("SET NAMES 'utf8'");
				$sponsoren = mysql_query("SELECT * FROM sponsoren WHERE student IS NULL OR student = '' ORDER BY `plz` ASC, `firmenname` ASC") or die(mysql_error());
				
				while ($sponsor = mysql_fetch_assoc($sponsoren)) {
					echo '<li draggable="true" class="listitem" data-index="', $sponsor['id'], '">', $sponsor['firmenname'], ' - ', $sponsor['strasse'], ' - ', $sponsor['plz'], ' - ', $sponsor['ort'], '</li>';
				}
			?>
		</ul>
	</div>
</section>
<div id="middle"></div>
<section id="right" class="clearfix">
	<div class="scroll">
		<h2>Schüler</h2>
		<?php
			$students = mysql_query("SELECT * FROM students ORDER BY `plz` ASC, `klasse` ASC, `nachname` ASC") or die(mysql_error());
			
			while ($student = mysql_fetch_assoc($students)) {
				echo '<div class="dropperContainer"><div class="itemDropper" data-index="', $student['id'], '"><div class="front"><img src="', ($student['avatar'] == '0') ? 'img/user' : 'http://dl.dropbox.com/u/21062820/avatar/'.$student['id'], '.png" alt="Avatar" />', $student['nachname'], ' ', $student['vorname'], '</div></div></div>';
			}
			
			mysql_close($mysql);
		?>
	</div>
</section><!-- wenn included, dann werden die Sachen unten nicht eingefuegt