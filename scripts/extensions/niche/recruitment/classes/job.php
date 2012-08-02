<?php
class Job {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($jid=NULL) {
		if($jid != NULL) {
			$rs = mq("select * from " . DB_TBL_JOBS . " where jid='$jid'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				foreach($rw as $arg=>$val) {
					$this->data[$arg] = stripslashes($val);
				}
				$rsi = mq("select jp_inputname, jp_value from " . DB_TBL_JOB_PROPERTIES . " p inner join " . DB_TBL_JOB_PROPERTIES_MASTER . " m on m.jpid=p.jpid where jid='$jid'");
				while($rwi = mfa($rsi)) {
					$this->data[$rwi['jp_inputname']] = $rwi['jp_value'];
				}
				$this->data['safetitle'] = urlencode($this->data['j_title']);
				$this->data['link'] = "job/" . $jid . "/" . $this->data['safetitle'] . "/";
				$this->data['apply_link'] = "apply/" . $jid . "/" . $this->data['safetitle'] . "/";
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
    public function __set($arg, $val) {
        if ($arg == "jid") { return; }
		if($arg == "cid") {
			$rs = mq("delete from " . DB_TBL_CATEGORY_LINK . " where l_type='job' and uid='" . $this->data['jid'] . "'");
			$val = (array)$val;
			foreach($val as $cid) {
				$rsi = mq("insert into " . DB_TBL_CATEGORY_LINK . " (uid,cid,l_type) values ('" . $this->data['jid'] . "','$cid','job')");
			}
		} else { 
	        $val = checkIfStrIsDate($val);
			$val = mres((string)$val);
			
			$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_JOBS . "' AND COLUMN_NAME = '$arg'");
			if(mnr($rs) > 0) {
				$rsu = mq("update " . DB_TBL_JOBS . " set $arg='$val' where jid='" . $this->data['jid'] . "'");
			} else {
				$rs = mq("SELECT jpid FROM " . DB_TBL_JOB_PROPERTIES_MASTER . " WHERE jp_inputname = '$arg'");
				if(mnr($rs) > 0) {
					$rw = mfa($rs);
					$rsc = mq("select * from " . DB_TBL_JOB_PROPERTIES . " where jpid='" . $rw['jpid'] . "' and jid='" . $this->data['jid'] . "'");
					if(mnr($rsc) > 0) {
						$rwc = mfa($rsc);
						$rsu = mq("update " . DB_TBL_JOB_PROPERTIES . " set jp_value='$val' where jpiid='" . $rwc['jpiid'] . "'");
					} else {
						$rsu = mq("insert into " . DB_TBL_JOB_PROPERTIES . " (jid,jpid,jp_value) values  ('" . $this->data['jid'] . "','" . $rw['jpid'] . "','$val')");
					}
		        }
			}
		}
	    $this->data[$arg] = $val;
    }

	public function the_excerpt($len=60) {
		return mb_substr($this->j_details,0,$len);
	}
	public function fullSalaryInfo() {
		return $this->currencySymbol() . $this->salary . " " . $this->salary_type;
	}
	public function currencySymbol() {
		switch ($this->currency) {
			case "GBP - British Pound":
				return "&pound;";
				break;
			case "EUR - Euro":
				return "&euro;";
				break;
			default:
				return substr($this->currency,0,3) . " ";
				break;
		}
	}

	public function updateJob($post_array) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
	    
		$jid = $this->data['jid'];
	    if($jid == "") {
	    	if(!isset($j_title)) { $j_title = ""; }
			
	    	if(!isset($j_postdate)) { $j_postdate = DB_SAFE_DATETIME; }
	    	if(!isset($j_startdate)) { $j_startdate = DB_SAFE_DATETIME; } else { $j_startdate = date("Y-m-d H:i:s",strtotime($j_startdate)); }
			
	    	if(!isset($j_expirydate)) {
	    		$date_end = date("Y-m-d H:i:s", strtotime(DB_SAFE_DATETIME . " +1 month"));
	    		$j_expirydate = $date_end;
			} else {
				$j_expirydate = date("Y-m-d H:i:s",strtotime($j_expirydate));
			}
	        $rsi = mq("insert into " . DB_TBL_JOBS . " (j_title,j_postdate,j_startdate,j_expirydate) values ('$j_title','$j_postdate','$j_startdate','$j_expirydate')");
			$jid = miid();
			$this->data['jid'] = $jid;
			$_SESSION['_msg'] = "newjob";
	    } else {
			$_SESSION['_msg'] = "updatedjob";
	    }

	    foreach($post_array as $arg => $val) {
	    	$this->$arg = $val;
	    }
		
		$this->updateJobLocation($this->createFullAddress());
		
		$_SESSION['_mtype'] = "S";
		return $jid;
	}
	public function emailFriend($email) {
		$arr = $this->getJobDetailArray();
		$email = new Email('email-friend-a-job',$arr,$email,'Job: ' . $this->j_title);
		$email->sendEmail();
		$_SESSION['_mtype'] = "S";
		$_SESSION['_msg'] = "emailfriendsuccess";
		
	}
	public function deleteJob() {
		$jid = $this->data['jid'];
		if($jid > 0) {
	        $this->j_deleted = 1;
	        $this->j_active = 0;
	        $this->j_featured = 0;
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedjob";
		}
	}
	// BE CAREFUL !!! //
	// This function is not called from anywhere but included for learning purposes. If you want to call this note it will remove all knowledge that a job ever existed //
	public function removeJob() {
		$jid = $this->data['jid'];
		if($jid > 0) {
	        $rs = mq("delete from " . DB_TBL_JOBS . " where jid='$jid'");
	        $rs = mq("delete from " . DB_TBL_JOB_PROPERTIES . " where jid='$jid'");
	        $rs = mq("delete from " . DB_TBL_CATEGORY_LINK . " where uid='$jid'");
	        $rs = mq("delete from " . DB_TBL_APPLICATIONS . " where jid='$jid'");
	        $rs = mq("delete from " . DB_TBL_SAVED_JOBS . " where jid='$jid'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "removedjob";
		}
	}
	public function categoryArray() {
		if($this->data['jid'] != "") {
			return getCategoryArray($this->data['jid'],'job');
		}
	}
	public function updateJobLocation($address) {
		$rs = mq("SELECT * FROM " . DB_TBL_GEO_CACHE . " WHERE search LIKE '" . mres($address) . "'");
		if(mnr($rs) > 0) {
			$rw = mfa($rs);
			$this->lat = $rw['lat'];
			$this->lon = $rw['lon'];
			return true;
		}
		
		$out = curl_get_contents('http://maps.googleapis.com/maps/api/geocode/xml?sensor=false&address=' . urlencode($address));
		$xml = simplexml_load_string($out);
		if (!$xml) return false;
	
		if ((string)$xml->status === 'OVER_QUERY_LIMIT') {
			return $this->getLatLonYahoo($address);
			return false;
		}
		if ((string)$xml->status !== 'OK') return NULL;
	
		$dRad = CalculateDistance(
			array((float)$xml->result->geometry->viewport->southwest->lat,(float)$xml->result->geometry->viewport->southwest->lng),
			array((float)$xml->result->geometry->viewport->northeast->lat,(float)$xml->result->geometry->viewport->northeast->lng)
		);
		$dRad = $dRad / 2;
		$rs = mq("INSERT INTO " . DB_TBL_GEO_CACHE . " (search,lat,lon,default_radius) VALUES ('" . mres($address) . "','" . (float)$xml->result->geometry->location->lat . "','" . (float)$xml->result->geometry->location->lng . "','" . $dRad . "')");

		$this->lat = (float)$xml->result->geometry->location->lat;
		$this->lon = (float)$xml->result->geometry->location->lng;
		return true;
	}
	public function getLatLonYahoo($address) {
		$out = curl_get_contents('http://where.yahooapis.com/geocode?appid=aa6sMN6k&flags=X&q=' . urlencode($address));
		$xml = simplexml_load_string($out);
		if (!$xml || $xml->Found == 0) return false;
		
		$dRad = CalculateDistance(
			array((float)$xml->Result->boundingbox->south,(float)$xml->Result->boundingbox->west),
			array((float)$xml->Result->boundingbox->north,(float)$xml->Result->boundingbox->east)
		);
		$dRad = $dRad / 2;
		$rs = mq("INSERT INTO " . DB_TBL_GEO_CACHE . " (search,lat,lon,default_radius) VALUES ('" . mres($address) . "','" . (float)$xml->Result->latitude . "','" . (float)$xml->Result->longitude . "','" . $dRad . "')");
		$this->lat = (float)$xml->Result->latitude;
		$this->lon = (float)$xml->Result->longitude;
		return true;
	}
	public function createFullAddress() {
		$ret = "";
		if(isset($this->data['address1'])) $ret .= stripslashes($this->data['address1']);
		if(isset($this->data['address2'])) $ret .= ", " . stripslashes($this->data['address2']);
		if(isset($this->data['address3'])) $ret .= ", " . stripslashes($this->data['address3']);
		if(isset($this->data['town'])) $ret .= ", " . stripslashes($this->data['town']);
		if(isset($this->data['province'])) $ret .= ", " . stripslashes($this->data['province']);
		if(isset($this->data['country'])) $ret .= ", " . stripslashes($this->data['country']);
		if(isset($this->data['postcode'])) $ret .= ", " . stripslashes($this->data['postcode']);
		return $ret;
	}


	public static function getJobPropertyArray() {
		$job_properties = "";
		$rs = mq("SELECT * FROM " . DB_TBL_JOB_PROPERTIES_MASTER . " order by jp_section");
		while($rw = mfa($rs)) {
			$job_properties[$rw['jp_section']][] = array('jp_inputname' => $rw['jp_inputname'], 'jp_name' => $rw['jp_name'], 'jp_inputtype' => $rw['jp_inputtype']);
		}
		return $job_properties;
	}
	public static function showJobInput($in,$idname=NULL,$val=NULL) {
		$ret = "";
		$rs = mq("select * from " . DB_TBL_JOB_PROPERTIES_MASTER . " where jp_inputname='$in'");
		if(mnr($rs) > 0) {
			$rw = mfa($rs);
			if($idname == NULL) { $idname = $rw['jp_inputname']; }
			switch ($rw['jp_inputtype']) {
				case "text":
					$ret = "<input type=\"text\" id\"$idname\" name=\"$idname\" value=\"$val\" />";
					break;
				case "select":
					$ret = "<select name=\"$idname\" id=\"$idname\">";
					$rss = mq("select * from " . DB_TBL_SELECTBOX . " where s_type='" . $rw['jp_inputname'] . "' order by s_val");
					while($rws = mfa($rss)) {
						$ret .= "<option value=\"" . $rws['s_val'] . "\"";
						if($val != NULL) {
							 if($rws['s_val'] == $val) $ret .= " selected ";
						} else {
							if($rws['s_default'] == "1") $ret .= " selected";
						}
						$ret .= ">" . $rws['s_val'] . "</option>";
					}
					$ret .= "</select>";
					break;
				case "special":
					switch ($rw['jp_inputname']) {
						case "country":
							$ret = "<select name=\"$idname\" id=\"$idname\">";
							$rss = mq("select * from " . DB_TBL_COUNTRIES . " order by printable_name");
							while($rws = mfa($rss)) {
								$ret .= "<option value=\"" . $rws['iso'] . "\"";
								if($val != NULL) {
									 if($rws['iso'] == $val) $ret .= " selected ";
								} else {
									if($rws['isDefault'] == "1") $ret .= " selected";
								}
								$ret .= ">" . $rws['printable_name'] . "</option>";
							}
							$ret .= "</select>";
							break;
					}
					break;
			}
		}
		return $ret;
	}
	public static function getJobs($arr=NULL) {
		$ret = array();
		$wc = "1=1";
		
		if(isset($arr)) { extract($arr); }
			
		if(!isset($j_active)) { $wc .= " and j_active='1' "; }
		if(!isset($j_deleted)) { $wc .= " and j_deleted='0' "; }
		if(!isset($j_expirydate)) { $j_expirydate = DB_SAFE_DATETIME; }
		$wc .= " and j_expirydate >= '$j_expirydate' ";
		if(!isset($j_startdate)) { $j_startdate = DB_SAFE_DATETIME; }
		$wc .= " and j_startdate <= '$j_startdate' ";
		
		if(isset($scid)) {
			$jobsearch = new JobSearch($scid);
			$json = (array)json_decode($jobsearch->sc_arr);
			$arr = array_merge($arr,$json);
		}
		
		if(isset($arr)) {
			
			$ignoreForWC = array('j_active','j_deleted','j_expirydate','j_startdate');
			foreach($arr as $arg => $val) {
				if($val == "") { continue; }
				if(!in_array($arg,$ignoreForWC)) {
					$val = mres($val);
					$found = false;
					// Try job actual fields
					if(in_array($arg,$GLOBALS['job_all_fields'])) {
						switch($arg) {
							case "j_title":
								$wc .= " and j_title like '%$val%' ";
								break;
							default:
								$wc .= " and $arg = '$val' ";
								break;
						}
						$found = true;
					}
					
					// Try job property options
					if(!$found) {
						$key = array_search($arg,$GLOBALS['job_all_properties']);
						if($key) {
							switch($arg) {
								case "jobtype":
									if($val != "Any") {
										$wc .= " and jid in (select jid from " . DB_TBL_JOB_PROPERTIES . " where jpid='$key' and jp_value='$val') ";
									}
									break;
								default:
									$wc .= " and jid in (select jid from " . DB_TBL_JOB_PROPERTIES . " where jpid='$key' and jp_value='$val') ";
									break;
							}
							
							$found = true;
						}
					}
					
					// Other special cases
					if(!$found) {
						switch($arg) {
							case "j_keyword":
								$wc .= " and (j_title like '%$val%' or j_details like '%$val%') ";
								break;
							case "cid":
								$wc .= " and jid in (select uid from " . DB_TBL_CATEGORY_LINK . " where l_type='job' and cid='" . $val . "')";
								break;
						}
					}
				}
			}
		}
		
		//echo "select j.jid from " . DB_TBL_JOBS . " j where " . $wc;
		$rs = mq("select j.jid from " . DB_TBL_JOBS . " j where " . $wc);
		while($rw = mfa($rs)) {
			$ret[] = new Job($rw['jid']);
		}
		return $ret;
	}
	
	private function getJobDetailArray() {
		return $this->data;
	}
}

function curl_get_contents($url) {
	$ch = curl_init();
	$timeout = 5; // set to zero for no timeout
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	curl_close($ch);

	return $file_contents;
}
function CalculateDistance($cPos,$sPos,$unit = 'M') {
	// CODE TAKEN FROM uCore Plugin utopia.googlemaps: https://github.com/oridan/utopia.googlemaps/
	if (!$cPos || !$sPos) return NULL;
	if (is_string($cPos)) $cPos = getLatLon($cPos);
	if (is_string($sPos)) $sPos = getLatLon($sPos);
	list($lat1,$lon1) = array_values($cPos);
	list($lat2,$lon2) = array_values($sPos);

	if (!$lat1 || !$lon1 || !$lat2 || !$lon2) return NULL;

	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;

	$unit = strtoupper($unit);
	if ($unit == "K") {
		return ($miles * 1.609344);
	} else if ($unit == "N") {
		return ($miles * 0.8684);
	}

	return $miles;
}
?>