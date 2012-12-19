/**
 * Core JS rules that Liquid Recruitment calls.
 * Pre-installed packages call their relevant options, if they are enabled in Site Options. If they are not enabled they will not call their initialization functions.
 * You can safely remove any if() { ... } statements for the pre-installed packages if you wish to clean up the code, and if they are not enabled.
 */

$(document).ready(function() {
	
	/* Jquery UI elements - http://jqueryui.com/ */
    $('.datepicker').datepicker({
        dateFormat: 'dd-mm-yy'
    });
    $('.slider').slider();
    
    /* Pre-Installed Package : Dynamic ajax inclusion - https://github.com/filamentgroup/Ajax-Include-Pattern */
	if(use_ajaxinclude) {
		$("[data-append],[data-replace],[data-after],[data-before]").ajaxInclude();
	}
    
    /* Pre-Installed Package : Inline form validation - http://bassistance.de/jquery-plugins/jquery-plugin-validation/ */
    if(use_form_validation) {
    	applyFormRules();
    }
    
    /* Pre-Installed Package : Data tables  - http://datatables.net/ */
    if(use_table_parser) {
	    $('.dataTable').dataTable({
	        "bPaginate": true,
	        "bLengthChange": true,
	        "bFilter": true,
	        "bSort": true,
	        "bInfo": true,
	        "bAutoWidth": true,
	        "iDisplayLength": 5
			/*
			 "bProcessing": true,
	        "bServerSide": true,
	        "sAjaxSource": "scripts/location_of_ajax.php"
	        *
			*/
		});
	}
	
	/* Pre-Installed Package : Shows google map - http://code.google.com/apis/maps/documentation/javascript/ */
	if(use_google_maps) {
		mpc_name = "map_canvas"
		map_canvas = $('#' + mpc_name);
		if(map_canvas.length != 0) {
			var myOptions = {
				center: new google.maps.LatLng(-34.397, 150.644),
				zoom: 8,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			var map = new google.maps.Map(document.getElementById(mpc_name),myOptions);
		}
	}
	
	/* Pre-Installed Package : Shadowbox - http://www.shadowbox-js.com/ */
	if(use_shadowbox) {
		Shadowbox.init();
	}
	
	/* Pre-Installed Package : BX Content Slider - http://bxslider.com/ */
	if(use_bx_slider) {
		$('#slider1').bxSlider({
			speed: 500,
			auto: true,
			pager: true
		});
	}
	
	/* Pre-Installed Package : Flex Slider - http://www.woothemes.com/flexslider/ */
	if(use_flex_slider) {
		$('.flexslider').flexslider();
	}
});
