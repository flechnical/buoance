function openCinema() {
	document.getElementById('cinema').style.display = 'table';
}
function closeCinema() {
	document.getElementById('cinema').style.display = 'none';
}
function hasClass(element, cls) {
	return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}
function loadFunctions() {
	initDragListeners();
	initNanoScroller();
	closeCinema();
}
// $_SESSION-Variablen in JavaScript abspeichern
function getSessionData(variable) {
	return $.ajax({
		type: 'POST',
		url: '/functions/getsessiondata.php',
		async: false,
		data: {name: variable}
	}).responseText;
}