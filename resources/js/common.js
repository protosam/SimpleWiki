$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip({html: true});
});

$.fn.xcss = function(descriptor, value){
	var elem = $(this[0]);
	
	if(typeof(value) === 'undefined'){
		return $(elem).css(descriptor);
	}

	$(elem).css(descriptor, value);
	$(elem).css("-ms-"+descriptor, value);
	$(elem).css("-webkit-"+descriptor, value);
	$(elem).css("-o-"+descriptor, value);
	$(elem).css("-moz-"+descriptor, value);
	return this;
}
