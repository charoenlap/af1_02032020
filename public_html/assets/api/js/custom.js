jQuery(document).ready(function(){

	$('.li-apidoc-sidebar').on('click', function() {

	    $.smoothScroll({
	      	scrollTarget: '#row-'+$(this).data('value'),
	      	offset: -70
	    });
  	});
})
