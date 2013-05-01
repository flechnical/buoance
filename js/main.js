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
	$('.dropped .nano').nanoScroller({preventPageScrolling: true});
	
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
		$('.dropped .nano').nanoScroller({preventPageScrolling: true});
	});
	
	$('.dropped').hover(function() {
		$(this).find('.back').animate({'top': '0'}, 400);
	}, function() {
		$(this).find('.back').animate({'top': '194px'}, 400);
	});
	
}

var socket;

function socketVerbinden() {
	
	socket = io.connect('http://buoance_chat.jit.su'); // online: http://buoance.eu01.aws.af.cm / lokal: http://localhost:8000
	
	// on connection to server, ask for user's name with an anonymous callback
	socket.on('connect', function(){
		// call the server-side function 'adduser' and send one parameter (value of prompt)
		socket.emit('adduser', session['userid'], session['name']);
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

$(function() {
	
	$.ajax({
		type: 'POST',
		url: '/functions/getsessiondata.php',
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
	
});