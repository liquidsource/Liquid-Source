$(document).ready(function(){
	$('.dataTable').dataTable({
	    "bPaginate": true,
	    "bLengthChange": true,
	    "bFilter": true,
	    "bSort": true,
	    "bInfo": true,
	    "bAutoWidth": true,
	    "iDisplayLength": 20
	});
	
	var dontSort = [];
    $('.dataTable').each( function () {
        if ( $(this).hasClass( 'no_sort' )) {
            dontSort.push( { "bSortable": false } );
        } else {
            dontSort.push( null );
        }
    } );
    
    $('form').validate({
		
	});
	
    $(".timepicker").datetimepicker({
        dateFormat: 'dd-mm-yy'
    });
    
    $(".datepicker").datepicker({
        dateFormat: 'dd-mm-yy'
    });
});

var curUidNum = 0;
var chosenElements = new Array();
function rowSelect(uid) {
	inArray = false;
	if(chosenElements.indexOf(uid) != -1) { inArray = true; }
	if(!inArray) {
		chosenElements[curUidNum] = uid;
		curUidNum++;
		$('#row_' + uid + " td").css('background-color', '#B0E0E6');
	} else {
		curIndex = chosenElements.indexOf(uid);
		chosenElements[curIndex] = "";
		curUidNum--;
		$('#row_' + uid + " td").css('background-color', '#ffffff');
	}
}

function selectAll(uids) {
	newArray = uids.split(';');
	for(var i=0; i<newArray.length; i++) {
		if(newArray[i] != "") {
			rowSelect(newArray[i]);
		}
	}
}
function ls_admin_saveForm(prefix,posttype,frmName) {
	$('#' + prefix + "_posttype").val(posttype);
	$('#' + frmName).submit();
}
