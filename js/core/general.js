/**
 * This file is meant for all general js functions.
 * Pre-installed packages call their relevant options, if they are enabled in Site Options. If they are not enabled they will not call their initialization functions.
 * You can safely remove any if() { ... } statements for the pre-installed packages if you wish to clean up the code, and if they are not enabled.
 */

$(document).ready(function() {
	
});

/* This function is used on the example 'widgets' page. The function can safely be removed if you are not using the showWidget call that is found on that page */
function showWidget(widget_name) {
	$('#widget_example').hide('slow',function() {
		$('#widget_example').load("incs/widgets/" + widget_name + ".php", function() {
	    	$('#widget_example').show('slow');	
	    });
	});
}
