<section id="main" class="nano">
	<div class="content">
	<div class="padding">
		<h2>Sponsoren</h2>
		<ul id="sponsors">
		<?php
		if (!isset($_SESSION)) {
			session_start();
		}
		$userid = $_SESSION['userid'];
		
		require_once $_SERVER['DOCUMENT_ROOT'].'/comps/mysqlconnect.php';
		
		$query = mysql_query("SELECT * FROM sponsoren WHERE `student`='$userid'"); // ORDER BY nach Erstellungs- oder Fertigstellungsdatum sortieren (oder beides)
		
		if (mysql_num_rows($query) == 0) {
			echo 'Dir wurden noch keine Sponsoren zugeteilt.';
		} else {
			$summe = 0;
			while ($row = mysql_fetch_assoc($query)) {
				$id = $row['id'];
				$name = $row['firmenname'];
				$sponsern = $row['sponsern'];
				$sponsernenabled = ($sponsern) ? ' enabled' : '';
				$sponsernclicked = ($sponsern) ? ' clicked' : '';
				$sponserntrue = ($sponsern) ? 'true' : 'false';
				$sponserndisabled = (!$sponsern) ? ' disabled' : '';
				$ballheft = $row['ballheft'];
				$ballheftchecked = ($ballheft) ? ' checked' : '';
				$formatdisabled = (!$ballheft) ? ' disabled' : '';
				$format = $row['format_im_ballheft'];
				$flyer = $row['flyer'];
				$flyerchecked = ($flyer) ? ' checked' : '';
				$verlinkung = $row['verlinkung'];
				$verlinkungchecked = ($verlinkung) ? ' checked' : '';
				$betrag = $row['betrag'];
				$sachpreise = $row['sachpreise'];
				$sachpreiseenabled = ($sachpreise) ? ' enabled' : '';
				$sachpreiseclicked = ($sachpreise) ? ' clicked' : '';
				$sachpreisetrue = ($sachpreise) ? 'true' : 'false';
				$sachpreisedisabled = (!$sachpreise) ? ' disabled' : '';
				$spende = $row['spende'];
				$spendeenabled = ($spende) ? ' enabled' : '';
				$spendeclicked = ($spende) ? ' clicked' : '';
				$spendetrue = ($spende) ? 'true' : 'false';
				$spendedisabled = (!$spende) ? ' disabled' : '';
				$spendenbetrag = $row['spendenbetrag'];
				$zahlungsart = $row['zahlungsart'];
				$fertig = $row['fertig'];
				$abgeschlossen = $row['abgeschlossen'];
				$dokument = $row['dokument'];
				$notes = $row['notizen'];
				if ($notes) {
					$notesicon = <<<HERE
						<img src="/img/note.png" class="comment" />
HERE;
				} else {
					$notesicon = '';
				}
				if ($fertig) {
					$fertigicon = '<span class="status '.$id.'"><img src="/img/pending.png" /></span>';
					
				} else {
					$fertigicon = '<span class="status '.$id.'">_</span>';
				}
				if ($fertig == '0') {
					$fertigicon = '<span class="status wrong '.$id.'">x</span>';
				}
				if ($abgeschlossen) {
					$fertigicon = '<span class="status '.$id.'"><img src="/img/tick.png" /></span>';
				}
				$dokument = ($dokument) ? '<a href="https://dl.dropboxusercontent.com/u/21062820/sponsoring/'.$userid.'/'.$id.'.pdf">Dokument</a>' : 'Noch kein Dokument hochgeladen.';
				$sponsern = <<<HERE
					<div class="colheading$sponsernclicked $id sponsern" data-klasse="sponsern" data-enabled="$sponserntrue">Sponsern</div>
					<div class="colheading$sachpreiseclicked $id sachpreise" data-klasse="sachpreise" data-enabled="$sachpreisetrue">Sachpreise</div>
					<div class="colheading$spendeclicked $id spende" data-klasse="spende" data-enabled="$spendetrue">Spende</div>
					<div class="col sponsern$sponsernenabled">
						<input$sponserndisabled$ballheftchecked type="checkbox" class="formatenabler" id="ballheft$id" name="ballheft$id" value="$ballheft" /><label class="formatenabler" for="ballheft$id"> Ballheft</label><br />
						<input$formatdisabled type="text" class="format" id="format$id" name="format$id" value="$format" placeholder="Format..." /><br />
						<input$sponserndisabled$flyerchecked type="checkbox" id="flyer$id" name="flyer$id" value="$flyer" /><label for="flyer$id"> Flyer</label><br />
						<input$sponserndisabled$verlinkungchecked type="checkbox" id="verlinkung$id" name="verlinkung$id" value="$verlinkung" /><label for="verlinkung$id"> Verlinkung</label><br />
						<label for="betrag$id">Betrag</label><br />
						<input$sponserndisabled type="text" id="betrag$id" name="betrag$id" value="$betrag" />
					</div>
					<div class="col sachpreise$sachpreiseenabled">
						<input$sachpreisedisabled type="text" placeholder="Anzahl Name..." class="sachpreise$id" />
					</div>
					<div class="col spende$spendeenabled">
						<label for="spendenbetrag$id">Spendenbetrag</label>
						<input$spendedisabled type="text" id="spendenbetrag$id" name="spendenbetrag$id" value="$spendenbetrag" />
					</div>
					<div id="sider$id" class="sider">$dokument</div>
HERE;
				$fertig = <<<HERE
					<div class="confirmdata" data-id="$id">$sponsern</div>
HERE;
				$controls = <<<HERE
					<div class="controls">
						<span class="fertig"><img src="/img/green.png" /></span>
						<span class="undecided"><img src="/img/grey.png" /></span>
						<div class="submit" data-id="$id">Absenden</div>
					</div>
HERE;
				if ($notes) {
					$notes = <<<HERE
						<div class="notes">$notes</div>
HERE;
				} else {
					$notes = '';
				}
				$inhalt = <<<HERE
					<div class="panel">$notesicon$fertigicon</div>
					<div class="info">$fertig$notes$controls</div>
HERE;
				echo '<li class="filedropper"><span>', $row['firmenname'], '</span><div class="upload">Datei hochladen<input type="file" class="selector" accept="application/pdf" data-firmid="', $row['id'], '" /></div>', $inhalt, '</li>';			
				
				$summe += $betrag + $spendenbetrag;
			}
		}
		
		?>
		
		</ul>
		<?php echo ($summe) ? $summe : ''; ?>
		wenn $_SESSION['bereich'] == 'Sponsoring' dann bei Kernteam die Hauptsponsoren noch auflisten
	</div>
	</div>
</section>