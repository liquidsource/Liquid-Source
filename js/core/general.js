$(document).ready(function() {
	/* Jquery UI elements - http://jqueryui.com/ */
    $(".datepicker").datepicker({
        dateFormat: 'dd-mm-ysy'
    });
    $( ".slider" ).slider();
    
    /* Data tables  - http://datatables.net/ */
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
	
	/* Shows google map - http://code.google.com/apis/maps/documentation/javascript/ */
	if(use_google_maps) {
		var myOptions = {
			center: new google.maps.LatLng(-34.397, 150.644),
			zoom: 8,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
	}
	
	/* Shadowbox - http://www.shadowbox-js.com/ */
	if(use_shadowbox) {
		Shadowbox.init();
	}
	
	/* BX Content Slider - http://bxslider.com/ */
	if(use_bx_slider) {
		$('#slider1').bxSlider({
			speed: 500,
			auto: true,
			pager: true
		});
	}
	
	/* Flex Slider - http://www.woothemes.com/flexslider/ */
	if(use_flex_slider) {
		$('.flexslider').flexslider();
	}
});