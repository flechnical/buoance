/*
	addEvent-Funktion fuer leichteres Event-Handling
*/
// function addEvent (obj, type, fn){
	// if (obj.addEventListener){
		// obj.addEventListener(type, fn, false);
	// }
	// else if(obj.attachEvent){
		// obj.attachEvent('on' + type, function(){
			// return fn.call(obj, window.event);
		// });
	// }
// }

function valChat(e, partnerid, username) {
	if (!e) var e = window.event;
	if (e.keyCode) code = e.keyCode;
	else if (e.which) code = e.which;
	if (code == 13) {
		if (e.shiftKey === true) {
			return true;
		} else {
			sendChat(partnerid, username);
			return false;
		}
	}
}

function sendChat(partnerid, partnername) {
	var daten = new Array(); // 0 => Text, 1 => Datum, 2 => partnerid, 3 => partnername
	daten[0] = $('.chat.'+partnerid+' textarea').val();
	daten[1] = Math.round(+new Date()/1000);
	daten[2] = session['userid'];
	daten[3] = session['name'];
	socket.emit('sendchat', daten);
	$('.chat.'+partnerid+' .notes').append('<div class="text right new" title="'+daten[1]+'"><div title="'+daten[3]+'" class="profile"></div>'+daten[0]+'</div>');
	$('.text.new').animate({backgroundColor: '#ffffff'}, 2000).removeClass('new');
	$('.chat.'+partnerid+' textarea').val('');
	$('.chat.'+partnerid+' .nano').nanoScroller({preventPageScrolling: true, scroll: 'bottom'});
}

var chats = new Array();

function openChat(partnerid, username) {
	// resetCounter(partnerid, username);
	if (chats[partnerid] == undefined || chats[partnerid] == false) {
		$('.chat.'+partnerid+' textarea').focus();
		$('.chat.'+partnerid+' .nano').nanoScroller({preventPageScrolling: true, scroll: 'bottom'});
		
		if (session['userid'] < partnerid) {
			var id1 = session['userid'];
			var id2 = partnerid;
		} else {
			var id1 = partnerid;
			var id2 = session['userid'];
		}
		socket.emit('addroom', id1+'-'+id2, partnerid);
		chats[partnerid] = true;
		// getChat(partnerid, username);
	} else {
		chats[partnerid] = false;
	}
	$('.user.'+username).toggleClass('max');
}

function closeChat(partnerid, username) {
	closedindex = $('.chat.'+partnerid).attr('data-index');
	$('.chat.'+partnerid).remove();
	// clearInterval(interval[partnerid]);
	chatbox = $('.chat');
	for(i = 0; i < chatbox.length; i++) {
		if (i == 0) {
			boxindex = $('.chat').attr('data-index');
		} else {
			boxindex = $('.chat').next().attr('data-index');
		}
		if (boxindex > closedindex) {
			$('.chat').attr('data-index', boxindex-1).animate({left: '-=240'}, 200);
		}
	}
	index--;
}

resizedMiddle = false;

function initNanoScroller() {
	
	// fensterhoehe = $(window).height();
	// $('section, section .content').height(fensterhoehe-50+'px');
	// $('section .scroll').slimScroll({
		// height: fensterhoehe-50+'px',
		// alwaysVisible: true,
		// distance: '0',
		// opacity: .6,
		// color: '#000',
		// size: '10px'
	// });
	$('.nano').nanoScroller();
	
	$(window).resize(function(){
		// fensterhoehe = $(window).height();
		// $('section, section .content').height(fensterhoehe-50+'px');
		// $('section .scroll').slimScroll({
			// height: fensterhoehe-50+'px',
			// alwaysVisible: true,
			// distance: '0',
			// opacity: .6,
			// color: '#000',
			// size: '10px'
		// });
		$('.nano').nanoScroller();
		if (resizedMiddle) {
			leftwidth = $('#left').outerWidth();
			$('#middle div').css('left', leftwidth);
		}
	});
	
	if (typeof lastopen == 'undefined') lastopen = 1;
	dropped = document.getElementsByClassName('dropped');
	for (x = 0; x < dropped.length; x++) {
		for (i = 0; i < dropped[x].childNodes[0].childNodes.length; i++) {
			if (dropped[x].childNodes[0].childNodes[i].className == 'student') {
				studentsDiv = dropped[x].childNodes[0].childNodes[i];
				break;
			}
		}
		studentsDiv.onclick = function() {
			toggleFirms(this);
		};
	}
	
	$('#middle div').dblclick(function(){
		$('#resizeback').hide();
		$('#middle div').css({background: 'transparent'});
		$('#left').css('width', 'calc(50% - 2px)');
		$('#right').css('width', 'calc(50% - 2px)');
		$('#middle div').css('left', 'calc(50% - 2px)');
		resizedMiddle = true;
		initNanoScroller();
	});
	
	$('#middle div').mousedown(function(){
		$('#resizeback').show();
		$('#middle').css('position','static');
		$('#middle div').css('background','black');
	});
	$('#middle div').mouseover(function(){
		$(this).css('cursor','ew-resize');
	});
	$('#middle div').draggable({
		axis: 'x',
		cursor: 'ew-resize',
		containment: '#resizeback',
		stop: function(event, ui) {
			$('#resizeback').hide();
			$('#middle div').css({background: 'transparent'});
			backwidth = $('#wrapper').outerWidth();
			leftwidth2 = ui.position.left + 2;
			leftpercent = 100 / backwidth * leftwidth2;
			rightpercent = 100 - leftpercent;
			$('#left').css('width', 'calc('+leftpercent+'% - 2px)');
			$('#right').css('width', 'calc('+rightpercent+'% - 2px)');
			$('#middle div').css('left', 'calc('+leftpercent+'% - 2px)');
			resizedMiddle = true;
			initNanoScroller();
		}
	});
	
}

	// $('.dropped .itemDropper .student').click(function(){
		// hasTheClass = $(this).siblings('.firms').hasClass('lastopen');
		// console.log($(this).siblings('.firms'));
		// $(this).siblings('.firms').slideToggle().addClass('lastopen');
		// if (lastopen != 1 && !hasTheClass) {
			// lastopen.slideUp();
			// lastopen.removeClass('lastopen');
		// }
		// lastopen = $(this).siblings('.firms');
	// });

var socket;

function socketVerbinden() {
	
	socket = io.connect('http://buoance_chat.jit.su'); // online: http://buoance.eu01.aws.af.cm / lokal: http://localhost:8000
	
	// on connection to server, ask for user's name with an anonymous callback
	socket.on('connect', function(){
		// call the server-side function 'adduser' and send one parameter (value of prompt)
		var usernamelower = session['name'].toLowerCase();
		console.log(usernamelower);
		socket.emit('adduser', session['userid'], usernamelower);
	});
	
	// listener, whenever the server emits 'updatechat', this updates the chat body
	socket.on('updatechat', function(data) {
		$('.chat.'+data[2]+' .notes').append('<div class="text new" title="'+data[1]+'"><div title="'+data[3]+'" class="profile"></div>'+data[0]+'</div>');
		$('.chat.'+data[2]+' .nano').nanoScroller({preventPageScrolling: true, scroll: 'bottom'});
		$('.text.new').animate({backgroundColor: '#ffffff'}, 2000).removeClass('new');
	}); /* username durch die partnerid ersetzen / nur Daten von anderen mit updatechat holen */
			/* Zusammenstellung von sendchat.php */
	
	socket.on('updateconsole', function(data) {
		console.log(data);
	});
	
	// listener, whenever the server emits 'updateusers', this updates the username list
	socket.on('updateusers', function(data) {
		$('span.online').removeClass('online');
		$.each(data, function(key, value) {
			if (key != session['userid']) {
				$('.user.'+value+' .chatlabel span').addClass('online');
			}
		});
	});
	
}

function searchSponsors() {
	if (typeof searchTimeout != 'undefined') {
		clearTimeout(searchTimeout);
	}
	var term = $('#search').val();
	var xhr = new XMLHttpRequest();
	xhr.open('POST', '/functions/searchsponsors.php', true);
	var fd = new FormData();
	fd.append('term', term);
	xhr.send(fd);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			document.getElementById('sponsorsearch').innerHTML = xhr.responseText;
			$('.hstudent, .hplz').addClass('nofilter');
			$('#sponsorsearch').addClass('notags');
			startScript();
			loadFunctions();
		}
	};
	window.history.pushState({path: '/bearbeitung/'+term}, '', '/bearbeitung/'+term);
}

function clearSearch() {
	var userart = getSessionData('userart');
	var xhr = new XMLHttpRequest();
	xhr.open('GET', '/sites/'+userart+'/bearbeitung/tags.php', true);
	xhr.send();
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			document.getElementById('sponsorsearch').innerHTML = xhr.responseText;
			$('.hstudent, .hplz').removeClass('nofilter');
			$('#sponsorsearch').removeClass('notags');
			startScript();
			loadFunctions();
		}
	};
	window.history.pushState({path: '/bearbeitung'}, '', '/bearbeitung');
}

function initSponsorSearch() {
	$('#search').keypress(function() {
		if (typeof searchTimeout != 'undefined') {
			clearTimeout(searchTimeout);
		}
		searchTimeout = setTimeout(searchSponsors, 400);
	});
	$('#search').keyup(function(e) {
		if (e.keyCode == 8) {
			if (typeof searchTimeout != 'undefined') {
				clearTimeout(searchTimeout);
			}
			searchTimeout = setTimeout(searchSponsors, 400);
			if ($('#search').val() == '') {
				clearSearch();
				clearTimeout(searchTimeout);
			}
		}
	});
}

function setSearch(vorschlag) {
	document.getElementById('search').value = vorschlag;
	searchSponsors();
}

var emptyInput = true;
function changeFields(elem, e) {
	var klasse = elem.className;
	var anzahl = elem.value.length;
	var islast = $(elem).is(':last-child');
	var last = $('.'+klasse+':last-child');
	var lastval = $('.'+klasse+':last-child').val();
	var backspace = (e.keyCode == 8) ? true : false;
	if (anzahl == 0 && !islast) {
		$(elem).next().focus();
		$(elem).remove();
	} else if (emptyInput && anzahl >= 1 && !backspace && lastval != '') {
		$('<input type="text" class="'+klasse+'" />').insertAfter(last);
		emptyInput = false;
	}
}

function submitSponsorData(elem) {
	var id = elem.dataset.id;
	var sponsern = ($('.colheading.sponsern.'+id).attr('data-enabled') == 'true');
	var sachpreise = ($('.colheading.sachpreise.'+id).attr('data-enabled') == 'true');
	var spende = ($('.colheading.spende.'+id).attr('data-enabled') == 'true');
	var ballheft = false;
	var format = '';
	var flyer = false;
	var verlinkung = false;
	var betrag = '';
	var sachpreis = '';
	var spendenbetrag = '';
	if (!(sponsern || sachpreise || spende)) {
		alert('Keine Daten ausgewaehlt.');
		return false;
	}
	if (sponsern) {
		if ($('#ballheft'+id).is(':checked')) {
			ballheft = true;
			if ($('#format'+id).val() == '') {
				alert('Kein Format eingegeben.');
				return false;
			} else {
				format = $('#format'+id).val();
			}
		}
		if ($('#flyer'+id).is(':checked')) {
			flyer = true;
		}
		if ($('#verlinkung'+id).is(':checked')) {
			verlinkung = true;
		}
		if (!(ballheft || flyer || verlinkung)) {
			if ($('#betrag'+id).val() != '') {
				alert('Keine Sponsorleistungen ausgewaehlt.');
				return false;
			} else {
				alert('Sponsordaten nicht ausgefuellt.');
				return false;
			}
		} else {
			if ($('#betrag'+id).val() == '') {
				alert('Kein Betrag eingegeben.');
				return false;
			} else {
				betrag = $('#betrag'+id).val();
			}
		}
	}
	if (sachpreise) {
		if ($('.sachpreise'+id).length == 1 && $('.sachpreise'+id).val() == '') {
			alert('Kein Sachpreis angegeben.');
			return false;
		}
		k = 0;
		$('.sachpreise'+id).each(function() {
			if ($('.sachpreise'+id).length == k+1) return false;
			var inhalt = this.value;
			var anzahl = (isNaN(parseInt(inhalt.split(' ')[0]))) ? '1' : inhalt.split(' ')[0];
			var space = (isNaN(parseInt(inhalt.split(' ')[0]))) ? 0 : inhalt.indexOf(' ')+1;
			var delimiter = (k != $('.sachpreise'+id).length-2) ? '<#>' : '';
			sachpreis += anzahl+';'+inhalt.slice(space, inhalt.length)+delimiter;
			k++;
		});
	}
	if (spende) {
		if ($('#spendenbetrag'+id).val() != '') {
			spendenbetrag = $('#spendenbetrag'+id).val();
		} else {
			alert('Kein Spendenbetrag eingegeben.');
			return false;
		}
	}
	var xhr = new XMLHttpRequest();
	xhr.open('POST', '/functions/savesponsordata.php', true);
	var fd = new FormData();
	fd.append('id', id);
	fd.append('sponsern', sponsern);
	fd.append('sachpreise', sachpreise);
	fd.append('spende', spende);
	fd.append('ballheft', ballheft);
	fd.append('format', format);
	fd.append('flyer', flyer);
	fd.append('verlinkung', verlinkung);
	fd.append('betrag', betrag);
	fd.append('sachpreis', sachpreis);
	fd.append('spendenbetrag', spendenbetrag);
	xhr.send(fd);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			$('.status.'+id).html('<img src="/img/pending.png" />');
			elem.parentNode.innerHTML = xhr.responseText;
		}
	};
}

function initFileUploader() {
	$('.col.sachpreise').on('keyup', 'input', function(e) {
		changeFields(this, e);
	});
	$('.col.sachpreise').on('focus', 'input', function(e) {
		if (this.value == '') {
			emptyInput = true;
		} else {
			emptyInput = false;
		}
	});
	$('.submit').click(function() {
		submitSponsorData(this);
	});
	$('.filedropper > span').click(function() {
		$(this).toggleClass('clicked');
		$(this).siblings('.info').slideToggle(200);
	});
	$('.colheading').click(function() {
		var klasse = this.dataset.klasse;
		$(this).toggleClass('clicked');
		var disabled = ($(this).siblings('.'+klasse).hasClass('enabled')) ? true : false ;
		$(this).siblings('.'+klasse).toggleClass('enabled').find('input, select').not('.format').attr('disabled', disabled);
		this.dataset.enabled = !disabled;
	});
	$('.formatenabler').click(function() {
		var disabled = ($(this).is(':checked') || $(this).siblings('.formatenabler').is(':checked')) ? false : true ;
		$(this).siblings('.format').attr('disabled', disabled);
		if (!disabled) {
			$(this).siblings('.format').focus();
		}
	});
	$('.upload').click(function (){
		$(this).siblings('span').addClass('clicked');
		$(this).siblings('.info').slideDown(200);
	});
	user = getSessionData('userid');
	var selected = '';
	fileselect = document.getElementsByClassName('selector');
	filedropper = document.getElementsByClassName('filedropper');
	uploadbutton = document.getElementsByClassName('upload');
	// getElementById
	function $id(id) {
		return document.getElementById(id);
	}
	// call initialization file
	if (window.File && window.FileList && window.FileReader) {
		Init();
	}
	// initialize
	function Init() {
		for (i = 0; i < fileselect.length; i++) {
			// file select
			fileselect[i].addEventListener('change', FileSelectHandler, false);
		}
		for (x = 0; x < filedropper.length; x++) {
			var xhr = new XMLHttpRequest();
			// is XHR2 available?
			if (xhr.upload) {
				// file drop
				filedropper[x].addEventListener('dragenter', FileDragHover, false);
				filedropper[x].addEventListener('dragover', stopStandard, false);
				filedropper[x].addEventListener('dragleave', FileDragHover, false);
				filedropper[x].addEventListener('drop', FileSelectHandler, false);
			}
		}
		for (k = 0; k < uploadbutton.length; k++) {
			uploadbutton[k].addEventListener('click', clickFileHandler, false);
		}
	}

	function clickFileHandler(e) {
		e.target.getElementsByClassName('selector')[0].click();
	}

	function stopStandard(e) {
		e.stopPropagation();
		e.preventDefault();
	}
	
	// file drag hover
	function FileDragHover(e) {
		// waere fuer hover-Effekt
		$(e.target).parent('.filedropper').toggleClass('hover');
	}
	// file selection
	function FileSelectHandler(e) {
		// cancel event and hover styling
		FileDragHover(e)
		stopStandard(e);
		// fetch FileList object
		var files = e.target.files || e.dataTransfer.files;
		// process all File objects
		selected = (e.target.className != 'selector') ? $(e.target).parents('.filedropper').find('.upload')[0] : e.target.parentNode;
		f = files[0];
		UploadFile(f);
	}
	// for (i = 0; i < fileselect.length; i++) {
		// fileselect[i].onclick = function() {
			// selected = this;
		// };
	// }
	// upload JPEG files
	var prozent = 0;

	// bei window.resize die Pixel der background-position aendern / vllt. mit loop

	function UploadFile(file) {
		var xhr = new XMLHttpRequest();
		if (xhr.upload && file.type == 'application/pdf' && file.size <= 5000000) {
			// create progress bar
			var o = selected.parentNode;
			var progress = o.appendChild(document.createElement('p'));
			var progressspan = progress.appendChild(document.createElement('span'));
			progressspan.appendChild(document.createTextNode('Datei wird hochgeladen... '));
			progress.parentNode.firstChild.style.color = '#eee';
			// progress bar
			xhr.upload.addEventListener('progress', function(e) {
				var pc = parseInt(100 - (e.loaded / e.total * 100));
				prozent = pc;
				var linewidth = progress.offsetWidth;
				var pixel = linewidth / 100 * pc;
				progress.style.backgroundPosition = '-'+pixel+'px, right';
				if (pc == 0) {
					progressspan.innerHTML = 'Datei wird bei Dropbox gespeichert...';
				}
			}, false);
			// file received/failed
			xhr.onreadystatechange = function(e) {
				if (xhr.readyState == 4) {
					// hier den Pling-Sound abspielen und Nachricht senden / CSS aendern von .success und .failure
					progress.className = (xhr.status == 200 ? 'success' : 'failure');
					progressspan.innerHTML = (xhr.status == 200 ? 'Die Datei wurde erfolgreich abgespeichert.' : 'Es gab ein Problem beim Hochladen.');
					if (xhr.status = 200) {
						playSound('sound1');
						setTimeout(function() {progress.style.opacity = '0';}, 2000);
						setTimeout(function() {progress.parentNode.firstChild.style.color = 'black'; progress.style.display = 'none';}, 3000);
						$(o).find('#sider'+selected.lastChild.dataset.firmid)[0].innerHTML = '<a href="https://dl.dropboxusercontent.com/u/21062820/sponsoring/'+user+'/'+selected.lastChild.dataset.firmid+'.pdf">Dokument</a>';
					}
				}
			};
			// start upload
			xhr.open("POST", '/functions/uploadFileDropbox.php', true);
			xhr.setRequestHeader("X_FILENAME", file.name);
			var fd = new FormData();
			fd.append('file', file);
			fd.append('directory', user);
			fd.append('filename', selected.lastChild.dataset.firmid); // data-id reingeben und als 143.pdf speichern
			xhr.send(fd);
		}
	}
}

$(function() {
	
	$.ajax({
		type: 'POST',
		url: '/functions/getsessionidname.php',
		data: 'none',
		success: function(data) {
			session = new Array();
			if (data != 'false') {
				session_vars = data.split(';');
				for (i = 0; i < session_vars.length; i++) {
					session_vals = session_vars[i].split(': ');
					session[session_vals[0]] = session_vals[1];
				}
				socketVerbinden();
				// wenn user angemeldet => funktion wird ausgefuehrt
			}
		}
	});
	
	/*
		Pruefen ob der User angemeldet ist (mit session) hat nicht funktioniert
	*/
	
	$('header#mainheader h1.title, nav#menu').mouseover(function() {
		$('nav#menu').show();
		if (typeof closeTimeout != 'undefined') {
			clearTimeout(closeTimeout);
		}
	});
	$('header#mainheader h1.title, nav#menu').mouseout(function() {
		closeTimeout = setTimeout(function() {$('nav#menu').hide();}, 600);
	});
	
	if (typeof $('#search')[0] != 'undefined' && $('#search').val() != '') {
		searchSponsors();
	}
	
});