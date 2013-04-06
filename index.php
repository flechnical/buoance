<?php
header('Content-type: text/html; charset=utf-8');
include('comps/usersession.php');
?>
<!DOCTYPE html>
<!--[if lt IE 7]>			<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>				<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>				<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Admin-Ansicht</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="css/normalize.min.css">
	<link rel="stylesheet" href="css/main.css">
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>

	<script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>
<body>
	<!--[if lt IE 7]>
	<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
	<![endif]-->

	<header class="clearfix">
		<h1 class="title"><img src="img/scool.png" height="30px" alt="s'Cool Ball" /></h1>
		<div class="seperate"></div>
		<nav>
			<ul>
					<li><input type="text" id="search" placeholder="Suchbegriff eingeben..." /></li>
					<li><a href="#bearbeitung" class="cupid-green">Bearbeitung</a></li>
					<li><a href="#zuteilung" class="cupid-green">Zuteilung</a></li>
			</ul>
		</nav>
	</header>

	<aside id="sidekick">
		<?php include('comps/userinfo.php'); ?>
		<?php
		if (isset($_SESSION['name']))
		{
		?>
		<div id="freunde">
			<div id="friends">
				Kontakte
			</div>
			<div id="connected">
			</div>
			<div class="spacer"></div>
		</div>
		<?php
		}
		?>
		<div id="side-content">
			Werbung<br />
			Footer mit Logo und Informationen<br />
			History of Changes<br />
			Kommunikation
		</div>
		<div id="credits">
			<img src="img/buoance.png" width="120px" alt="buoance" /><br />
			&copy; 03.2013
		</div>
	</aside>
	
	<div id="wrapper">
	<div id="cinema"><div id="c_table">
		<div>
			<h2>Loading...</h2>
			<img src="img/loader.gif" alt="loader.gif" />
		</div>
	</div></div>
	<section id="left" class="clearfix">
	<div class="scroll">
		<h2>Sponsoren</h2>
		<ul>
			<?php
				require_once 'comps/constants.php';
			
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
	</section>
	</div>
	
	<script src="http://buoance_chat_test.jit.su/socket.io/socket.io.js"></script> <!-- online: http://buoance.eu01.aws.af.cm / lokal: http://localhost:8000 -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>

	<script src="js/base.js"></script>
	<script src="js/slimscroll.js"></script>
	<script src="js/dragndrop.js"></script>
	<script src="js/plugins.js"></script>
	<script src="js/main.js"></script>

	<script>
		var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
		g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>
	
</body>
</html>