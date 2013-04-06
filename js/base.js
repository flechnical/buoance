function closeCinema() {
	var wrapperItems = document.querySelectorAll('div#wrapper > *');
	for (i = 0; i < wrapperItems.length; i++) {
		wrapperItems[i].style['-webkit-filter'] = 'none';
		wrapperItems[i].style.filter = 'none';
	}
	document.getElementById('cinema').style.display = 'none';
}