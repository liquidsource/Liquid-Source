<?php
class Media {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($mdid=NULL) {
		if($mdid != NULL) {
			$rs = mq("select * from " . DB_TBL_MEDIA . " where mdid='$mdid'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				foreach($rw as $arg=>$val) {
					$this->data[$arg] = stripslashes($val);
				}
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
        if($arg == "mdid") { return; }
		
        if (isset($this->data[$arg])) {
        	$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_MEDIA . "' AND COLUMN_NAME = '$arg'");
			if(mnr($rs) > 0) {
        		$val = mres($val);
				$rs = mq("update " . DB_TBL_MEDIA . " set $arg='$val' where mdid='" . $this->data['mdid'] . "'");
			}
        }
		$this->data[$arg] = $val;
    }
	
	public function updateMedia($post_array,$files_array=NULL) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		
		$mdid = $this->data['mdid'];
	    if($mdid == "") {
	        $rs = mq("insert into " . DB_TBL_MEDIA . " (md_dateuploaded) values ('" . DB_SAFE_DATETIME . "')");
	        $mdid = miid();
			$this->data['mdid'] = $mdid;
			$_SESSION['_msg'] = "newmedia";
	    } else {
	    	$this->deleteMediaFile();
			$_SESSION['_msg'] = "updatedmedia";
	    }
	    foreach($post_array as $arg => $val) {
			$this->$arg = $val;
		}
		
	    if($files_array['newmedia']['name'] != "") {
			
	        $target_path = $lvl . "uploads/media/" . date("Y") . "/";
	        if(!file_exists($target_path)) mkdir($target_path,0777);
	        
	        $target_path = $lvl . "uploads/media/" . date("Y") . "/" . date("m") . "/";
	        if(!file_exists($target_path)) mkdir($target_path,0777);
			
			
	        $filename = basename($files_array['newmedia']['name']);
	        $fullurl = $target_path . $filename; 
	        while(file_exists($fullurl)) {
	            $rantext = genRandomString(5);
	            $filename = $rantext . "_" . basename($files['newmedia']['name']);
	            $fullurl = $target_path . $filename;
	        }
	
	        if(move_uploaded_file($files_array['newmedia']['tmp_name'], $fullurl)) {
	            $mdtype = $files_array['newmedia']['type'];
	            $mdsize = $files_array['newmedia']['size'];
	            $rs = mq("update " . DB_TBL_MEDIA . " set md_filename='$filename', md_type='$mdtype', md_filesize='$mdsize', md_folder='$target_path' where mdid='$mdid'");
	        }
	    }
	   	
	   	if(isset($post_array['cid']))  updateCategoryLink($post_array['cid'],$mdid,'media');
		
		$_SESSION['_mtype'] = "S";
		return $mdid;
	}
	public function categoryArray() {
		if($this->data['mdid'] != "") {
			return getCategoryArray($this->data['mdid'],'media');
		}
		return ;
	}
	public function categoryNames() {
		return getCategoryNames($this->data['mdid'],'media',',');
	}
	public function inCategory($catids) {
		return inCategory($catids,$this->data['mdid'],'media');
	}
	public function getLocation() {
		if($this->data['mdid'] != "") {
			return $this->data['md_folder'] . $this->data['md_filename'];
		}
		return ;
	}

	public function deleteMedia() {
		if(isset($this->data['mdid'])) {
			$rs = mq("update " . DB_TBL_MEDIA . " set md_active='0' where mdid='" . $this->data['mdid'] . "'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedmedia";
			return true;
		}
		return false;
	}
	public function removeMedia() {
		$success = $this->deleteMediaFile();
		if($success) {
			$rs = mq("delete from " . DB_TBL_MEDIA . " mdid='" . $this->data['mdid'] . "'");
			$rs = mq("delete from " . DB_TBL_CATEGORY_LINK . " where uid='" . $this->data['mdid'] . "' and l_type='media'");
		}
		return $success;
	}
	private function deleteMediaFile() {
		$suc = true;
		$fileloc = $this->getLocation();
		if($fileloc != "") {
			$suc = unlink(INCLUDE_WEB_ROOT . $fileloc);
		}
		return $suc;
	}
	
}
?>