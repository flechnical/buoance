function startScript() {
	var ajaxLinks = document.getElementsByClassName('ajaxlink');
	for (i = 0; i < ajaxLinks.length; i++) {
		ajaxLinks[i].onclick = function(e) {
			if (getSessionData('status') == 'angemeldet') {
				// e.preventDefault();
				openCinema();
				var pageurl = this.getAttribute('href');
				var pathname = (this.getAttribute('href') == '/') ? '/index' : this.getAttribute('href');
				var xhr = new XMLHttpRequest();
				// xhr.addEventListener('progress', updateProgress, false);

				// // progress on transfers from the server to the client (downloads)
				// function updateProgress (oEvent) {
					// if (oEvent.lengthComputable) {
						// var percentComplete = oEvent.loaded / oEvent.total;
						// console.log(percentComplete);
					// } else {
						// console.log('Die Groesse ist nicht feststellbar.');
					// }
				// }
				
				page = pathname.split('/'); // [0] ==> ""
				if (page.length > 3) { // URL/____/____/____ ==> "" / "____" / "____" / "____"
					if (page[1] == 'bearbeitung') {
						xhr.open('GET', '/sites/'+page[1]+'.php?klasse='+page[2]+'&student='+page[3], true);
					}
				} else if (page.length > 2) { // URL/____/____
					if (page[1] == 'bearbeitung') {
						xhr.open('GET', '/sites/'+page[1]+'.php?klasse='+page[2], true);
					}
				} else { // URL/____
					xhr.open('GET', '/sites/'+page[1]+'.php', true);
				}
				
				// besser für jeden / einen GET reinstellen ?asdf=asdf&asdf=asdf ...
				
				xhr.send();
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {
						document.getElementById('content').innerHTML = xhr.responseText;
						startScript();
						loadFunctions();
					}
				};
				if (pageurl != window.location) {
					window.history.pushState({path: pageurl}, '', pageurl);
				}
			}
			
			return false; // normalem Link-Klick vorbeugen
			
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
}

window.onload = startScript();
window.onload = loadFunctions();