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
	initSponsorSearch();
	initFileUploader();
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
function toggleFirms(elem) {
	hasTheClass = $(elem).siblings('.firms').hasClass('lastopen');
	$(elem).siblings('.firms').slideToggle().addClass('lastopen');
	if (lastopen != 1 && !hasTheClass) {
		lastopen.slideUp();
		lastopen.removeClass('lastopen');
	}
	lastopen = $(elem).siblings('.firms');
}
function playSound(soundObj) {
  var sound = document.getElementById(soundObj);
	sound.play();
}