/**
 * Function: submitForm()
 * Function to call when submitting a form.
 * Usage: onsubmit="return submitForm(<form_name>)" on the form tag.
 *  - ie <form onsubmit="return submitForm('register_form')" method="post">
 *  - Add in case statement below for the form name.
 * 
 * Notes:
 *   Four example forms that come with the base install are included below. You can safely remove these if the forms are not used.
 */
function submitForm(frmName) {
    ls_overallFormSubmitFail = false;
    switch(frmName) {
        case "login_frm":
            failed = checkBlank('username');
            failed = checkBlank('password');
            break;
        case "reg_frm":
            failed = checkBlank('r_username');
            failed = checkSame('r_password','r_password_t');
            failed = checkSame('r_email','r_email_t');
            break;
        case "forgot_frm":
            failed = checkBlank('fgt_email');
            break;
        case "contact_frm":
            failed = checkBlank('cu_emaily');
            failed = checkBlank('cu_msg');
            break;
        default:
        	checkAdminForms(frmName); // Do not remove
        	break;
    }
    return formSubmitCheck(frmName); // Do not remove
}

/**
 * Do not edit below this line
 */

var ls_overallFormSubmitFail = false;
function formSubmitCheck(frmName) {
    if(!ls_overallFormSubmitFail) {
    	if(use_form_parser) {
    		newAction = $('#' + frmName).attr('action') + "&g_timeloaded=" + $('#p_timeloaded').val();
	    	$('#' + frmName).attr('action',newAction);
    	}
	    return true;
   	} else { return false; }
}
function checkAdminForms(frmName) {
    overallFail = false;
    switch(frmName) {
        case "ls_setup_form":
            failed = checkBlank('admin_username');
            failed = checkBlank('admin_password');
            failed = checkBlank('your_email');
            failed = checkSame('admin_password','admin_password_r');
            break;
        case "admin_media_frm":
        	failed = checkBlank('cur_file');
        	if(failed) {
        		ls_overallFormSubmitFail = false;
            	failed = checkBlank('newmedia');
        	}
        	break;
        case "admin_newuser_frm":
        	failed = checkBlank('cur_mid');
        	if(failed) {
        		ls_overallFormSubmitFail = false;
            	failed = checkBlank('m_password');
        	}
        	failed = checkEmail('m_email');
        	failed = checkBlank('m_username');
        	failed = checkChecked('m_type');
        	break;
        case "admin_link_frm":
        	failed = checkBlank('l_link');
        	break;
        case "admin_page_frm":
        	failed = checkBlank('pg_meta_title');
        	failed = checkChecked('pg_type');
        	break;
        case "admin_post_frm":
        	failed = checkBlank('p_title');
        	break;
        case "admin_template_frm":
        	failed = checkBlank('p_title');
        	break;
	}
}
function checkBlank(idn) {
    if($('#' + idn).val() == "") {
        $('#' + idn).addClass('input_error');
        ls_overallFormSubmitFail = true;
        return true;
    } else {
        $('#' + idn).removeClass('input_error');
        return false;
    }
}
function checkSame(idn_o, idn_t) {
    if(checkBlank(idn_o) || checkBlank(idn_t)) {
        return false;
    } else {
        if($('#' + idn_o).val() != $('#' + idn_t).val()) {
            $('#' + idn_o).addClass('input_error');
            $('#' + idn_t).addClass('input_error');
            ls_overallFormSubmitFail = true;
            return true;
        } else {
            $('#' + idn_o).removeClass('input_error');
            $('#' + idn_t).removeClass('input_error');
            return false;
        }
    }
}
function checkEmail(idn) {
    str = $('#' + idn).val();
    if((str.indexOf(".") > 2) && (str.indexOf("@") > 0)) {
        $('#' + idn).removeClass('input_error');
    } else {
        $('#' + idn).addClass('input_error');
        ls_overallFormSubmitFail = true;
        return true;
    }
}
function checkChecked(idn) {
    if(!$('input[name=' + idn + ']:checked').val()) {
        $('.' + idn).addClass('input_error');
        ls_overallFormSubmitFail = true;
        return true;
    } else {
        $('.' + idn).removeClass('input_error');
    }
}