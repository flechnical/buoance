function startScript() {
	var ajaxLinks = document.getElementsByClassName('ajaxlink');
	for (i = 0; i < ajaxLinks.length; i++) {
		ajaxLinks[i].onclick = function(e) {
			if (getSessionData('status') == 'angemeldet' && e.which == 1) {
				// e.preventDefault();
				openCinema();
				var userart = getSessionData('userart');
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
					if (page[1] == 'kontrolle') {
						xhr.open('GET', '/sites/'+userart+'/'+page[1]+'.php?klasse='+page[2]+'&student='+page[3], true);
					}
				} else if (page.length > 2) { // URL/____/____
					if (page[1] == 'kontrolle') {
						xhr.open('GET', '/sites/'+userart+'/'+page[1]+'.php?klasse='+page[2], true);
					}
				} else { // URL/____
					xhr.open('GET', '/sites/'+userart+'/'+page[1]+'.php', true);
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
			
			if (e.which == 1) return false; // normalem Link-Klick vorbeugen
			
		};
	}
	
	/*
		speziell fuer Zuteilung mit Filtern fuer Sponsor und Schueler
	*/
	
	// var sponsorLocation = '__'; --> beide sind in zuteilung.php definiert
	// var studentLocation = '__';
	var filterTags = document.getElementsByClassName('filtertag');
	for (i = 0; i < filterTags.length; i++) {
		filterTags[i].onclick = function(e) {
			if (getSessionData('status') == 'angemeldet' && e.which == 1) {
				// e.preventDefault();
				// openCinema();
				var userart = getSessionData('userart');
				var locationPlz = this.dataset.locationplz;
				var locationOrt = this.dataset.locationort;
				var seite = (hasClass(this, 'left')) ? 'left' : 'right';
				var xhr = new XMLHttpRequest();
				
				// wenn Strg -> fuer beide Filter setzen und in beide reinladen
				xhr.open('GET', '/sites/'+userart+'/zuteilung/'+seite+'/liste.php?plz='+locationPlz+'&ort='+locationOrt, true);
				
				xhr.send();
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {
						document.querySelector('#'+seite+' .tagwrapper').className = 'listcontainer';
						document.querySelector('#'+seite+' .listcontainer').innerHTML = xhr.responseText;
						startScript();
						loadFunctions();
					}
				};
				
				if (seite == 'left') {
					sponsorLocation = locationPlz;
				} else {
					studentLocation = locationPlz;
				}
				
				filter = document.querySelector('#'+seite+' .filter');
				filterTitle = document.querySelector('#'+seite+' .filter span');
				filter.style.display = 'block';
				filterTitle.innerHTML = locationPlz+' '+locationOrt;
				
				pageurl = '/zuteilung/'+sponsorLocation+'/'+studentLocation;
				
				if (pageurl != window.location) {
					window.history.pushState({path: pageurl}, '', pageurl);
				}
				
			}
			
			if (e.which == 1) return false; // normalem Link-Klick vorbeugen
			
		};
	}
	
	// bei Klick auf Filter loeschen Tags reinladen und startScript(); loadFunctions();
	
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

// wenn auf tag geklickt filter setzen
// vllt. noch URL dabei aendern
// bei Klick mit Strg bei beiden sections Filter reinladen

// bei dragndrop noch das zuruecknehmen reinstellen oder einfach einen loeschen-Button reingeben