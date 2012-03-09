//  Andy Langton's show/hide/mini-accordion @ http://andylangton.co.uk/jquery-show-hide

// this tells jquery to run the function below once the DOM is ready
$(document).ready(function() {

// choose text for the show/hide link - can contain HTML (e.g. an image)
var showText='SHOW';
var hideText='HIDE';


// append show/hide links to the element directly preceding the element with a class of "toggle"

$('.toggle').each(function() {
	el = $(this);
	el_prev_id = el.prev().attr("id");
	
	$.ajax({
	    type: "POST",
	    async: false,
	    url: "scripts/ajaxcall.php?ajaxcall=getshowhidemenu&menu=" + el_prev_id,
	    success: function(data) {
	    	if(data == "SHOW") {
	    		list = $('#sh_' + el_prev_id).hide();
	    	}
			el.prev().prepend(' <a href="#" class="toggleLink" id="slide_' + el_prev_id + '">'+data+'</a>');
	    }
	});
});

$('a.toggleLink').click(function() {
	el = $(this);
	menu_id = el.attr('id').substring(6);
	list = $('#sh_' + menu_id);
	
	// change the link text depending on whether the element is shown or hidden
	if (el.text()==showText) {
		el.text(hideText);
		list.slideDown('slow');
	}
	else {
		el.text(showText);
		list.slideUp('slow');
	}
	$.ajax({
	    type: "POST",
	    url: "scripts/ajaxcall.php?ajaxcall=showhidemenu&menu=" + menu_id,
	    success: function(data) {
	    	
	    }
	});
	
	// return false so any link destination is not followed
	return false;

});
});