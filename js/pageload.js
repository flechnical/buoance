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
				// if (page.length > 3) { // URL/____/____/____ ==> "" / "____" / "____" / "____"
					// if (page[1] == 'kontrolle') {
						// xhr.open('GET', '/sites/'+userart+'/'+page[1]+'.php?klasse='+page[2]+'&student='+page[3], true);
					// }
				// } else if (page.length > 2) { // URL/____/____
					// if (page[1] == 'kontrolle') {
						// xhr.open('GET', '/sites/'+userart+'/'+page[1]+'.php?klasse='+page[2], true);
					// }
				// } else { // URL/____
					xhr.open('GET', '/sites/'+userart+'/'+page[1]+'.php', true);
				// }
				
				if (page[1] != 'bearbeitung') { // damit beim Wechsel von Bearbeitung auf Zuteilung die Variablen wieder geloescht sind
					sponsorPlz = undefined;
					sponsorOrt = undefined;
					studentId = undefined;
					studentFirst = undefined;
					studentLast = undefined;
				} else {
					sponsorPlz = '__';
					sponsorOrt = '__';
					studentId = '__';
					studentFirst = '__';
					studentLast = '__';
				}
				
				if (page[1] == 'zuteilung') {
					sponsorLocation = '__';
					studentLocation = '__';
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
				var get = (seite == 'left') ? 'sponsoren' : 'students';
				var xhr = new XMLHttpRequest();
				
				// wenn Strg -> fuer beide Filter setzen und in beide reinladen
				xhr.open('GET', '/sites/'+userart+'/zuteilung/'+seite+'/liste.php?'+get+'='+locationPlz, true);
				
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
				
				$('#'+seite+' h2').addClass('filter');
				filterTitle = document.querySelector('#'+seite+' .filter');
				filterTitle.innerHTML = locationPlz+'<br />'+locationOrt;
				$('#'+seite+' .filter').append('<span class="closefilter"></span>');
				
				pageurl = '/zuteilung/'+sponsorLocation+'/'+studentLocation;
				
				if (pageurl != window.location) {
					window.history.pushState({path: pageurl}, '', pageurl);
				}
				
			}
			
			if (e.which == 1) return false; // normalem Link-Klick vorbeugen
			
		};
	}
	
	if (typeof sponsorPlz != 'undefined') {
		if (sponsorPlz != '__' || studentId != '__') {
			searchBox = document.getElementById('search');
			searchBox.disabled = true;
			searchBox.style.opacity = '.4';
		}
	}
	
	var searchFilter = document.getElementsByClassName('searchfilter');
	for (i = 0; i < searchFilter.length; i++) {
		searchFilter[i].onclick = function(e) {
			if (getSessionData('status') == 'angemeldet' && e.which == 1) {
				// e.preventDefault();
				// openCinema();
				var userart = getSessionData('userart');
				if (typeof this.dataset.student != 'undefined') { // wenn der Filter fuer einen Schueler gesetzt wurde...
					studentId = this.dataset.studentid;
					studentFirst = this.dataset.studentfirst;
					studentLast = this.dataset.studentlast;
					seite = 'student';
					title = studentFirst+' '+studentLast;
				} else {
					sponsorPlz = this.dataset.locationplz;
					sponsorOrt = this.dataset.locationort;
					seite = 'plz';
					title = sponsorPlz+' '+sponsorOrt;
				}
				
				if (e.ctrlKey) {
					$('.h'+seite).addClass('filter');
					filterTitle = document.querySelector('.h'+seite+'.filter .textnode');
					filterTitle.innerHTML = title;
					$('.h'+seite+'.filter .textnode').attr('title', title);
					$('.h'+seite+'.filter').append('<span class="closefilter"></span>');
					
					searchBox = document.getElementById('search');
					searchBox.disabled = true;
					searchBox.style.opacity = '.4';
					document.querySelector('.d'+seite).style.visibility = 'hidden';
					document.querySelector('.d'+seite).style.height = '0';
					loadFunctions();
					// vorher noch die Filter rechts durch Uebereinstimmungen ersetzen / nur wenn auch Schueler dazu gefunden werden
						// evtl.
					return false;
				}
				
				var xhr = new XMLHttpRequest();
				
				// if (sponsorPlz != '__' && studentId != '__') { // beide Filter gesetzt
					var parameter = 'sponsoren='+sponsorPlz+'&students='+studentId; // wird dann in php-Datei gemacht
				// } else if (sponsorPlz != '__') {
					// var parameter = 'sponsoren='+sponsorPlz;
				// } else {
					// var parameter = 'students='+studentId;
				// }
				xhr.open('GET', '/sites/'+userart+'/bearbeitung/liste.php?'+parameter, true);
				
				xhr.send();
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {
						$('.h'+seite).addClass('filter');
						filterTitle = document.querySelector('.h'+seite+'.filter .textnode');
						filterTitle.innerHTML = title;
						$('.h'+seite+'.filter .textnode').attr('title', title);
						$('.h'+seite+'.filter').append('<span class="closefilter"></span>');
						if (studentId == '__') $('.hstudent').addClass('nofilter');
						if (sponsorPlz == '__') $('.hplz').addClass('nofilter');
						
						searchBox = document.getElementById('search');
						searchBox.disabled = true;
						searchBox.style.opacity = '.4';
						document.querySelector('#sponsorsearch').innerHTML = xhr.responseText;
						$('#sponsorsearch').addClass('notags');
						startScript();
						loadFunctions();
					}
				};
				
				pageurl = '/bearbeitung/'+sponsorPlz+'/'+studentId;
				
				if (pageurl != window.location) {
					window.history.pushState({path: pageurl}, '', pageurl);
				}
				
			}
			
			if (e.which == 1) return false; // normalem Link-Klick vorbeugen
			
		};
	}
	
	var notags = document.getElementsByClassName('notags')[0];
	if (notags) {
		var searchResult = notags.getElementsByTagName('li');
		if (searchResult.length) {
			var deleter = document.createElement('span');
			deleter.appendChild(document.createTextNode('Löschen'));
			var deleterDiv = document.createElement('div');
			deleterDiv.appendChild(deleter);
			deleterDiv.className = 'deleter';
		}
		for (i = 0; i < searchResult.length; i++) {
			(function(index){
				searchResult[i].onmouseover = function(e) {
					target = searchResult[index];
					if (e.shiftKey) {
						deleterArray = document.getElementsByClassName('deleter');
						for (x = 0; x < deleterArray.length; x++) {
							deleterArray[x].style.display = 'none';
						}
						target.appendChild(deleterDiv);
						target.childNodes[4].style.display = 'block';
						document.querySelector('div.deleter span').onclick = function(e) {
							console.log('clicked for deletion');
						};
					} else if (!target.childNodes[4] || target.childNodes[4].style.display != 'block') {
						target.onclick = function(e) {
							console.log('clicked for change');
						};
					}
				};
				searchResult[i].onmouseout = function(e) {
					target = searchResult[index];
					if (e.shiftKey) {
						if (target.childNodes[4]) {
							target.childNodes[4].style.display = 'none';
							document.querySelector('div.deleter span').onclick = '';
						}
					}
					target.onclick = '';
				}
			})(i);
		}
	}
	
	var controlLink = document.getElementsByClassName('controllink');
	for (i = 0; i < controlLink.length; i++) {
		controlLink[i].onclick = function(e) {
			if (e.which == 1) {
				
				var userart = getSessionData('userart');
				
				var controllocation = this.getAttribute('href');
				var getdata = controllocation.split('/kontrolle/')[1];
				var art = (getdata.indexOf('/') != -1) ? 'student' : 'klasse';
				var get = (art == 'student') ? getdata.split('/')[1] : getdata;
				
				var xhr = new XMLHttpRequest();
				
				xhr.open('GET', '/sites/'+userart+'/kontrolle/'+art+'.php?'+art+'='+get, true);
				
				xhr.send();
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {
						document.getElementById('control').innerHTML = xhr.responseText;
						startScript();
						loadFunctions();
					}
				};
				
				if (controllocation != window.location) {
					window.history.pushState({path: controllocation}, '', controllocation);
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