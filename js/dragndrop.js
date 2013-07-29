/*
	wenn schon etwas in Liste bei itemDropper dann hinzufuegen und nicht neue Liste machen
		- die dropper Klasse anhaengen an dropperContainer
	
	fuer Firefox: http://stackoverflow.com/questions/3977596/how-to-make-divs-in-html5-draggable-for-firefox
	Cross-Browser versuchen
	Animation bei Hover über itemDropper zum verdeutlichen dass es dahin verschoben wird
	mehrere Dinge auch mit ziehen markieren koennen und mit STRG statt nur klicken
	Liste in itemDropper stylen
*/

function hasClass(element, cls) {
	return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}
function insertActive(elem) {
	var items = '';
	var item = document.getElementsByClassName('listitem');
	for (i = 0; i < item.length; i++) {
		if (hasClass(item[i], 'active')) {
			var index = item[i].dataset.index;
			items += (items !== '') ? ', '+index : index;
		}
	}
	var activeItems = document.getElementsByClassName('listitem active');
	if (activeItems.length == 0) {
		items = theone;
		number = 1;
	}
	
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			var content = '<ul class="back">';
			sponsoren = xhr.responseText.split(', ');
			for (s = 0; s < sponsoren.length; s++) {
				inhalt = document.querySelector('li[data-index="'+sponsoren[s]+'"]').innerHTML;
				content += '<li>'+inhalt+'</li>';
			}
			if (activeItems.length == 0) {
				var the_one = document.querySelector('li[data-index="'+theone+'"]');
				the_one.className = 'listitem invisible';
				setTimeout(function() {
					the_one.className = 'listitem hidden';
				}, 400);
			} else {
				while (activeItems.length > 0) {
					activeItems[0].className = 'listitem invisible';
				}
				var invisibleItems = document.getElementsByClassName('listitem invisible');
				setTimeout(function() {
					while (invisibleItems.length > 0) {
						invisibleItems[0].className = 'listitem hidden';
					}
				}, 400);
			}
			content += '</ul>';
			elem.innerHTML += content;
			elem.parentNode.className = 'dropperContainer dropped';
		}
	};
	
	xhr.open("POST", '/php/db_zuteilung.php', true);
	var fd = new FormData();
	fd.append('items', items);
	fd.append('stud', elem.dataset.index);
	xhr.send(fd);
	
	mouse_is_inside = false;
}
function startDrag(e, clicked) {
	var activeItems = document.getElementsByClassName('listitem active');
	if (!hasClass(clicked, 'active') && activeItems.length > 0) {
		e.preventDefault();
		return false;
	}
	var number = (activeItems.length > 0) ? activeItems.length : 1;
	var image = document.getElementById('dragimage');
	var canvas = document.createElement('canvas');
	canvas.width = image.width;
	canvas.height = image.height;
	var x = canvas.width / 2;
	var y = canvas.height / 2;
	var context = canvas.getContext('2d');
	context.drawImage(image, 0, 0);
	context.beginPath();
	context.rect(4, 12, 40, 24);
	context.fillStyle = 'rgba(0,0,0,.4)';
	context.fill();
	context.lineWidth = 1;
	context.fillStyle = 'white';
	context.font = 'bold 16pt sans-serif';
	context.textAlign = 'center';
	context.textBaseline = 'middle';
	context.fillText(number, x, y);
	var canvasImage = new Image();
	canvasImage.src = canvas.toDataURL('image/png');
	e.dataTransfer.setDragImage(canvasImage, 0, 0);
}
var theone = '';
var lastitem = '';
var mouse_is_inside = false;
document.onclick = function() {
	if (!mouse_is_inside) {
		var activeItems = document.getElementsByClassName('listitem active');
		while (activeItems.length > 0)
			activeItems[0].className = 'listitem';
	}
};
function initDragListeners() {
	dropper = document.getElementsByClassName('itemDropper');
	for (i = 0; i < dropper.length; i++) {
		dropper[i].addEventListener('dragenter', function(e) { e.preventDefault(); }, false);
		dropper[i].addEventListener('dragover', function(e) { e.preventDefault(); }, false);
		dropper[i].addEventListener('drop', function() { insertActive(this); }, false);
	}
	var item = document.getElementsByClassName('listitem');
	for (i = 0; i < item.length; i++) {
		item[i].addEventListener('dragstart', function(e) { startDrag(e, this); }, false);
		item[i].onmouseover = function() {
			if (hasClass(this, 'active')) {
				mouse_is_inside = true;
			}
		};
		item[i].onmouseout = function() {
			mouse_is_inside = false;
		};
		item[i].onmousedown = function(e) {
			var index = (this.dataset) ? this.dataset.index : this.getAttribute('data-index');
			theone = index;
			mouse_is_inside = true;
		};
		(function(index){
			item[i].onclick = function(e) {
				var activeItems = document.getElementsByClassName('listitem active');
				if (!e.ctrlKey) {
					while (activeItems.length > 0)
						activeItems[0].className = 'listitem';
					if (e.shiftKey) {
						if (lastitem < index) {
							for (s = lastitem; s < index; s++) {
								item[s].className = 'listitem active';
							}
						} else {
							for (s = lastitem; s > index; s--) {
								item[s].className = 'listitem active';
							}							
						}
					}
				}
				this.className = (hasClass(this, 'active')) ? 'listitem' : 'listitem active';
				if (!e.shiftKey && hasClass(this, 'active')) lastitem = index;
			};
		})(i);
	}
	// hier den loader wieder schließen, weil alle Funktionen geladen sind // vllt. noch ausbauen auf guten loader mit Sachen die ueberprueft werden sollen // Modul ... fertig geladen // wenn array durchgegangen fertig
}