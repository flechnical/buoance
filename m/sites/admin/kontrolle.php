<?php

if (!isset($_SESSION)) {
	session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'].'/comps/mysqlconnect.php';

?>
<section id="main" class="nano">
	<div class="content">
	<div class="padding">
		<h2>Kontrolle</h2>
		<ul id="control">
		<?php if (isset($_GET['student'])) { 
			
			include $_SERVER['DOCUMENT_ROOT'].'/sites/'.$_SESSION['userart'].'/kontrolle/student.php';
			
		} else if (isset($_GET['klasse'])) { 
		
			include $_SERVER['DOCUMENT_ROOT'].'/sites/'.$_SESSION['userart'].'/kontrolle/klasse.php';
			
		} else { ?>
			<li><a href="/kontrolle/ak" class="controllink">4A HAK</a></li>
			<li><a href="/kontrolle/bk" class="controllink">4B HAK</a></li>
			<li><a href="/kontrolle/ck" class="controllink">4C HAK</a></li>
			<li><a href="/kontrolle/dk" class="controllink">4D HAK</a></li>
			<li><a href="/kontrolle/ek" class="controllink">4E HAK</a></li>
		<?php } ?>
		</ul>
	</div>
	</div>
</section>