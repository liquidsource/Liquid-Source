<?php
/**
 * Countries class
 */
echo "included";
class Country {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($iso=NULL) {
		if($iso != "") {
			$rs = mq("select * from " . DB_TBL_COUNTRIES . " where iso='$iso'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				$this->data['iso'] = $rw['iso'];
				$lang_code = $rw['lang_code'];
				if($lang_code == "") $lang_code = $rw['iso'];
				$this->data['lang_code'] = $lang_code;
				$this->data['name'] = $rw['name'];
				$this->data['printable_name'] = $rw['printable_name'];
				$this->data['numcode'] = $rw['numcode'];
				$this->data['isDefault'] = $rw['isDefault'];
				$this->data['isActive'] = $rw['isActive'];
				$this->data['flagImg'] = $rw['flagImg'];
				$this->data['englishLanguage'] = $rw['englishLanguage'];
			}
			
		}
	}
	public function __get($arg) {
		$m = "get_$arg";
		if(method_exists($this, $m)) return $this->$m();
        if (isset($this->data[$arg])) {
            return $this->data[$arg];
        }
    }
	
	/* STATIC PUBLIC FUNCTIONS */
	public static function activeCountryOptions($chosen=NULL,$dontshowiso=NULL) {
		$arr = Country::getActiveCountriesArray();
		foreach($arr as $country) {
			$selected = "";
			if($dontshowiso == $country->iso) { } else {
				if($chosen == $country->iso) { $selected = "selected"; }
				$ret .= "<option value='{$country->iso}' $selected>{$country->printable_name}</option>";
			}
		}
		return $ret;
	}
	public static function getActiveCountriesArray() {
		$rs = mq("select iso from " . DB_TBL_COUNTRIES . " where isActive='1'");
		while($rw = mfa($rs)) {
			$ret[] = new Country($rw['iso']);
		}
		return $ret;
	}
	public static function getAllCountriesArray($arroptions = NULL) {
		
		$sql_orderby = " printable_name asc";
		if($arroptions != NULL) {
			$arr = extract($arroptions);
			if($orderby != "")  { $sql_orderby = $orderby; }
		}
		$ret = array();
		$rs = mq("select * from " . DB_TBL_COUNTRIES . " order by " . $sql_orderby);
		while($rw = mfa($rs)) {
			$ret[] = new Country($rw['iso']);
		}
		return $ret;
	}
	public static function getDefaultCountry() {
		echo "trying";
		die;
		$rs = mq("select iso, lang_code from " . DB_TBL_COUNTRIES . " where isDefault='1'");
		if(mnr($rs) > 0) {
			$rw = mfa($rs);
			$iso = $rw['iso'];
		} else {
			$rs = mq("update " . DB_TBL_COUNTRIES . " set isDefault='1' where iso='gb'");
			$iso = "gb";
		}
		$country = new Country($iso);
		return $country;
	}
	
	public static function getCommonCountries($common,$incActive=1) {
		$rs = mq("select iso from " . DB_TBL_COUNTRIES . " where isCommon='$common' and isActive='$incActive' order by printable_name");
		while($rw = mfa($rs)) {
			$iso = $rw['iso'];
			$ret[] = new Country($iso);
		}
		return $ret;
	}
	public static function showDefaultFlag($h="24px") {
		$iso = Country::getDefaultCountry();
		$country = new Country($iso->iso);
		echo "<img src='" . BASE_HREF . "images/flags/{$country->flagImg}' style='height:$h;' >";
	}
}
?>