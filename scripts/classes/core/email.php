<?php
class Email {
	public $em_slug, $em_message, $em_message_formatted, $em_to, $em_subject;
	
	public function __construct($slug=NULL,$posty=NULL,$to=NULL,$subject=NULL) {
		if($slug != NULL) {
			$this->em_slug = $slug;
			
			extract($posty);
		    $rs = mq("select * from " . DB_TBL_POSTS . " where p_active='1' and p_slug='$slug' and p_type='template'");
		    if(mnr($rs) > 0) {
		        $rw = mfa($rs);
		        $msg = stripslashes($rw['p_content']);
				$this->$em_message = $msg;
		        foreach($posty as $arg => $val) {  $msg = str_replace("[" . $arg . "]",$val,$msg); }
				$this->$em_message_formatted = $msg;
		    }
			
			if($to != NULL) $this->em_to = $to;
			if($subject != NULL) $this->em_subject = $subject;
		}
	}
	public function setEmailTo($to) { $this->em_to = $to; }
	public function setSubject($subj) { $this->em_subject = $subj; }
	
	public function sendEmail() {
		$this->checkMailInjection();
		$msg_inner = $this->formatEmailMessage();
	
		$headers .= "MIME-Version: 1.0\r\n"; 
		$headers .= "Content-Type: text/HTML; charset=utf8\r\n";
		$headers = "From: \"". EM_FROM_NAME ."\" <". EM_FROM_EMAIL .">\r\n"; 
	
	    $msg = $this->getEmailHead();
	    $msg .= $this->getEmailBodyHead();
	    $msg .= $msg_inner;
	    $msg .= $this->getEmailFooter();
		$msg .= $this->getEmailBottom();
		
	    if($this->em_to != NULL && $this->em_subject != NULL) {
			mail($this->em_to,'=?UTF-8?B?'.base64_encode($this->em_subject).'?=',$msg,$headers);
	    }
	}
	public function sendRawEmail($to,$subject,$msg) {
		$this->em_to = $to;
		$this->em_subject = $subject;
		$this->em_message = $msg;
		$this->em_message_formatted = $msg;
		$this->em_slug = 'temp';
		
		$this->sendEmail();
	}
	private function formatEmailMessage($msg=NULL) {
		if($msg == NULL) $msg = $this->em_message_formatted;
		
		$msg = $this->str_style_replace("<img","outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;",$msg);
		$msg = $this->str_style_replace("<p","margin:1em 0",$msg);
		
		$msg = $this->str_style_replace("<h1","color: " . EM_HEADER_COLOR . " !important;",$msg);
		$msg = $this->str_style_replace("<h2","color: " . EM_HEADER_COLOR . " !important;",$msg);
		$msg = $this->str_style_replace("<h3","color: " . EM_HEADER_COLOR . " !important;",$msg);
		$msg = $this->str_style_replace("<h4","color: " . EM_HEADER_COLOR . " !important;",$msg);
		$msg = $this->str_style_replace("<h5","color: " . EM_HEADER_COLOR . " !important;",$msg);
		$msg = $this->str_style_replace("<h6","color: " . EM_HEADER_COLOR . " !important;",$msg);
		
		$msg = $this->str_style_replace("<td","border-collapse: collapse",$msg);
		$msg = $this->str_style_replace("<a","color:" . EM_LINK_COLOR . ";",$msg);
		
		$this->em_message_formatted = $msg;
		return $msg;
	}
	private function getEmailHead() {
		$str = "<!DOCTYPE html PUBLIC \\\"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\"> 
		<html xmlns=\"http://www.w3.org/1999/xhtml\">
		<head>
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
			<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
			<title>Your Message Subject or Title</title>
			<style type=\"text/css\">
				#outlook a {padding:0;} 
				body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;} 
				.ExternalClass {width:100%;} 
				.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
				#backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
		
				
				img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;} 
				a img {border:none;} 
				.image_fix {display:block;}
		
				p {margin: 1em 0;}
		
				h1, h2, h3, h4, h5, h6 {color: " . EM_HEADER_COLOR . " !important;}
				h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: " . EM_LINK_COLOR . " !important;}
		
				h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
				color: " . EM_LINK_HOVER_COLOR . " !important;
				}
		
				h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
				color: " . EM_LINK_COLOR . " !important;
				}
		
				table td {border-collapse: collapse;}
		
				a {color: " . EM_LINK_COLOR . ";}
		
			</style>
		</head>
		<body>
		";
		return $str;
	} 
	
	private function getEmailBodyHead() {
		$str = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"backgroundTable\">
			<tr>
			<td valign=\"top\">
			<table style='width:" . EM_WIDTH . "' width='" . EM_WIDTH . "'><tr><td style='width:" . EM_WIDTH . "'>
			";
		return $str;
	}
	private function getEmailFooter() {
		$str = "
		</td></tr></table>
		</td>
		</tr>
		</table>";
		return $str;
	}
	private function getEmailBottom() {
		$str = "
		</body>
		</html>";
		return $str;
	}
	
	private function checkMailInjection($value) { if(eregi("TO:", $value) || eregi("CC:", $value) || eregi("CCO:", $value) || eregi("Content-Type", $value)) exit("ERROR: Code injection attempt denied! Please don't use the following sequences in your message: 'TO:', 'CC:', 'CCO:' or 'Content-Type'.");  }
	private function str_style_replace($f,$style,$msg) { str_replace($f,$f . " style='$style' ",$msg); }
}


/*
EmailAddressValidator Class
http://code.google.com/p/php-email-address-validation/
Released under New BSD license
http://www.opensource.org/licenses/bsd-license.php

Sample Code
----------------
$validator = new EmailAddressValidator;
if ($validator->check_email_address('test@example.org')) {
    // Email address is technically valid
}
*/
class EmailAddressValidator {
    public function check_email_address($strEmailAddress) {
        if (preg_match('/[\x00-\x1F\x7F-\xFF]/', $strEmailAddress)) { return false; }
        if (!$this->check_text_length($strEmailAddress, 3, 256)) { return false; }
        $intAtSymbol = strrpos($strEmailAddress, '@');
        if ($intAtSymbol === false) { return false; }
        $arrEmailAddress[0] = substr($strEmailAddress, 0, $intAtSymbol);
        $arrEmailAddress[1] = substr($strEmailAddress, $intAtSymbol + 1);
        $arrTempAddress[0] = preg_replace('/\./','' ,$arrEmailAddress[0]);
        $arrTempAddress[0] = preg_replace('/"[^"]+"/','' ,$arrTempAddress[0]);
        $arrTempAddress[1] = $arrEmailAddress[1];
        $strTempAddress = $arrTempAddress[0] . $arrTempAddress[1];
        if (strrpos($strTempAddress, '@') !== false) { return false; }
        if (!$this->check_local_portion($arrEmailAddress[0])) { return false; }
        if (!$this->check_domain_portion($arrEmailAddress[1])) { return false; }
        return true;
    }

    protected function check_local_portion($strLocalPortion) {
        if (!$this->check_text_length($strLocalPortion, 1, 64)) { return false; }
        $arrLocalPortion = explode('.', $strLocalPortion);
        for ($i = 0, $max = sizeof($arrLocalPortion); $i < $max; $i++) {
             if (!preg_match('.^('
                            .    '([A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]' 
                            .    '[A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]{0,63})'
                            .'|'
                            .    '("[^\\\"]{0,62}")'
                            .')$.'
                            ,$arrLocalPortion[$i])) {
                return false;
            }
        }
        return true;
    }
    protected function check_domain_portion($strDomainPortion) {
        if (!$this->check_text_length($strDomainPortion, 1, 255)) { return false; }
        if (preg_match('/^(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])'
           .'(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}$/'
           ,$strDomainPortion) || 
            preg_match('/^\[(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])'
           .'(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}\]$/'
           ,$strDomainPortion)) {
            return true;
        } else {
            $arrDomainPortion = explode('.', $strDomainPortion);
            if (sizeof($arrDomainPortion) < 2) { return false; }
            for ($i = 0, $max = sizeof($arrDomainPortion); $i < $max; $i++) {
                if (!$this->check_text_length($arrDomainPortion[$i], 1, 63)) { return false; }
                if (!preg_match('/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|'
                   .'([A-Za-z0-9]+))$/', $arrDomainPortion[$i])) {
                    return false;
                }
                if ($i == $max - 1) {
                    if (strlen(preg_replace('/[0-9]/', '', $arrDomainPortion[$i])) <= 0) { return false; }
                }
            }
        }
        return true;
    }
    protected function check_text_length($strText, $intMinimum, $intMaximum) {
        $intTextLength = strlen($strText);
        if (($intTextLength < $intMinimum) || ($intTextLength > $intMaximum)) { return false;
        } else { return true; }
    }

}
?>