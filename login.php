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
	
	<section id="wrapper">
		<h1>
			Anmelden
		</h1>
		<form action="comps/loginscript.php" method="post">
			<label for="name">Username: </label><input type="text" name="name" id="name" /><br /><br />
			<label for="pass">Passwort: </label><input type="password" name="pass" id="pass" /><br /><br />
			<label for="keepin">Angemeldet bleiben </label><input type="checkbox" name="keepin" id="keepin" /><br /><br />
			<input type="submit" value="Anmelden" />
		</form>
	</section>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>

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
<?php

?>