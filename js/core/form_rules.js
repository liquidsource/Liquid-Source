function applyFormRules() {
	$('#reg_frm').validate({
		rules: {
			m_email: {
				required: true,
				email: true
			},
			m_email_t: {
				required: true,
				email: true,
				equalTo: "#m_email"
			},
			m_password: {
				required: true,
				minlength: 6
			},
			m_password_t: {
				required: true,
				minlength: 6,
				equalTo: "#m_password"
			}
		}
	});
	$('#login_frm').validate({
		rules: {
			l_email: {
				required: true,
				email: true
			},
			l_password: {
				required: true,
				minlength: 6
			}
		}
	});
	$('#forgot_frm').validate({
		rules: {
			fgt_email: {
				required: true,
				email: true
			}
		}
	});
	$('#contact_frm').validate({
		rules: {
			cu_email: {
				required: true,
				email: true
			},
			cu_msg: {
				required: true
			}
		}
	});
	
	$('#apply_frm').validate({
		rules: {
			a_cl: {
				required: true
			},
			a_email: {
				required: true
			}
		}
	});
}
