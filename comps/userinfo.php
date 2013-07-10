<?php
	if (isset($_SESSION['status']) && $_SESSION['status'] == 'angemeldet') {
	
	echo '<div id="userinfo" class="in">';
		echo '<a href="/user/', $_SESSION['name'], '"><img src="', ($_SESSION['avatar'] == '0') ? '/img/user' : 'http://dl.dropbox.com/u/21062820/avatar/'.$_SESSION['userid'], '.png" alt="Avatar" /></a>';
		echo '<span id="greeting">Hallo <span style="font-weight: bold;">', $_SESSION['name'], '</span></span><br />';
		echo '<a href="/settings">Einstellungen</a><br />';
		echo '<a class="logout" href="/logout">Abmelden</a>';
	echo "</div>";
	
	} else {
		
	echo '<div id="userinfo" class="out">';
		echo '<img src="/img/user.png" alt="Gastaccount" title="Sie sind nicht angemeldet und haben daher nur einen Gastaccount." />';
		echo '<a class="login ajaxlink" href="/login">Anmelden</a><br /><a class="signup ajaxlink" href="/signup">Registrieren</a>';
	echo '</div>';
		
	}
?>