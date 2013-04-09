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

function valChat(e, partnerid) {
	if (!e) var e = window.event;
	if (e.keyCode) code = e.keyCode;
	else if (e.which) code = e.which;
	if (code == 13) {
		if (e.shiftKey === true) {
			return true;
		} else {
			sendChat(partnerid);
			return false;
		}
	}
}

function sendChat(partnerid) {
	text = $('.chat.'+partnerid+' textarea').val();
	date = Math.round(+new Date()/1000);
	socket.emit('sendchat', text, partnerid);
	$('.chat.'+partnerid+' .notes').append('<b>'+session['name']+'</b>: '+date+' - '+text+'<br />');
	$('.chat.'+partnerid+' textarea').val('');
}

index = 0;

function openChat(partnerid, username) {
	// resetCounter(partnerid, username);
	if ($('.chat.'+partnerid).length == 0) {
		$('body').append('<div class="chat '+partnerid+'" data-index="'+index+'"><div class="infolabel">'+username+'<div onclick="closeChat('+partnerid+', \''+username+'\');">X</div></div><div class="slimscroller"><div class="notes"></div></div><textarea name="text" onkeydown="return valChat(event, '+partnerid+');"></textarea></div>');
		$('.chat.'+partnerid).slideDown(200);
		texthoehe = $('.chat.'+partnerid+' textarea').outerHeight();
		$('.chat.'+partnerid+' textarea').focus();
		$('.chat.'+partnerid+' .slimscroller, .chat.'+partnerid+' div.slimScrollDiv').height(240-texthoehe+'px');
		$('.chat.'+partnerid).height('264px');
		$('.chat.'+partnerid+' .slimscroller').slimScroll({height:240-texthoehe+'px'});
		$('.chat.'+partnerid).css('left', index*240+120+'px');
		index++;
		
		$('div.infolabel').click(function() {
			$(this).parent().toggleClass('minimized');
		});
		
		if (session['userid'] < partnerid) {
			var id1 = session['userid'];
			var id2 = partnerid;
		} else {
			var id1 = partnerid;
			var id2 = session['userid'];
		}
		socket.emit('addroom', id1+'-'+id2, partnerid);
		// getChat(partnerid, username);
	} else {
		$('.chat.'+partnerid).height('264px');
	}
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

function initSlimScroll() {
	
	fensterhoehe = $(window).height();
	$('section, section .scroll, section div.slimScrollDiv').height(fensterhoehe-50+'px');
	$('section .scroll').slimScroll({
		height: fensterhoehe-50+'px',
		alwaysVisible: true,
		distance: '0',
		opacity: .6,
		color: '#000',
		size: '10px'
	});
	
	$(window).resize(function(){
		fensterhoehe = $(window).height();
		$('section, section .scroll, section div.slimScrollDiv').height(fensterhoehe-50+'px');
		$('section .scroll').slimScroll({
			height: fensterhoehe-50+'px',
			alwaysVisible: true,
			distance: '0',
			opacity: .6,
			color: '#000',
			size: '10px'
		});
	});
	
}

var socket;

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
	
	function socketVerbinden() {
		
		socket = io.connect('http://buoance_chat_test.jit.su'); // online: http://buoance.eu01.aws.af.cm / lokal: http://localhost:8000
		
		// on connection to server, ask for user's name with an anonymous callback
		socket.on('connect', function(){
			// call the server-side function 'adduser' and send one parameter (value of prompt)
			socket.emit('adduser', session['userid'], session['name']);
		});
		
		// listener, whenever the server emits 'updatechat', this updates the chat body
		socket.on('updatechat', function(chatid, data) {
			if (chatid != session['userid']) { // ueberfluessig?
				$('.chat.'+chatid+' .notes').append('<b>'+chatid + ':</b> ' + data + '<br />');
			}
		}); /* username durch die partnerid ersetzen / nur Daten von anderen mit updatechat holen */
				/* Zusammenstellung von sendchat.php */
		
		socket.on('updateconsole', function(data) {
			console.log(data);
		});
		
		// listener, whenever the server emits 'updateusers', this updates the username list
		socket.on('updateusers', function(data) {
			$('#connected').empty();
			$.each(data, function(key, value) {
				if (key != session['userid']) {
					$('#connected').append('<div class="user '+value+' new" onclick="openChat(\''+key+'\', \''+value+'\')">'+key+' - '+value+'<img src="/img/green.png" alt="on" /></div>');
				}
			});
		});
		
	}
	
});