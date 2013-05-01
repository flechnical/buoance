Modernizr.addTest('csscalc', function() {
	var prop = 'width:';
	var value = 'calc(10px);';
	var el = document.createElement('div');

	el.style.cssText = prop + Modernizr._prefixes.join(value + prop);

	return !!el.style.length;
});

function sizeElements() {
	var height = window.innerHeight;
	var width = window.innerWidth;
	var header = document.getElementById('mainheader'); // width
	var nav = document.getElementById('menu'); // width
	var wrapper = document.getElementById('wrapper'); // height and width
	var left = document.getElementById('left'); // width
	var right = document.getElementById('right'); // width
	header.style.width = width-200+'px';
	nav.style.width = width-463+'px';
	wrapper.style.height = height-50+'px';
	wrapper.style.width = width-200+'px';
	left.style.width = (width-204)/2+'px';
	right.style.width = (width-204)/2+'px';
}

if (!Modernizr.csscalc) {
	window.onload = sizeElements();
	window.onresize = function() {sizeElements();};
}