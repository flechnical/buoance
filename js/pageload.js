function startScript() {
	var ajaxLinks = document.getElementsByClassName('ajaxlink');
	for (i = 0; i < ajaxLinks.length; i++) {
		ajaxLinks[i].onclick = function(e) {
			// e.preventDefault();
			var pageurl = this.getAttribute('href');
			var pathname = (this.getAttribute('href') == '/') ? 'index' : this.getAttribute('href');
			var xhr = new XMLHttpRequest();
			xhr.open('GET', '/sites/'+pathname+'.php', true);
			xhr.send();
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					document.getElementById('content').innerHTML = xhr.responseText;
					loadFunctions();
				}
			};
			if (pageurl != window.location) {
				window.history.pushState({path: pageurl}, '', pageurl);
			}
			return false;
		};
	}
	// window.onpopstate = function(e) {
		// var pathname = (location.pathname == '/') ? 'index' : location.pathname;
		// var xhr = new XMLHttpRequest();
		// xhr.open('GET', '/sites/'+pathname+'.php', true);
		// xhr.send();
		// xhr.onreadystatechange = function() {
			// if (xhr.readyState == 4 && xhr.status == 200) {
				// document.getElementById('content').innerHTML = xhr.responseText;
				// doFirst();
			// }
		// };
	// };
	loadFunctions(); // wenn Seite geladen und ajax-script geladen
}

window.onload = startScript();