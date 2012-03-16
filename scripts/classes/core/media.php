<?php
class Media {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($mdid=NULL) {
		if($mdid != NULL) {
			$rs = mq("select * from " . DB_TBL_MEDIA . " where mdid='$mdid'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				$this->data['id'] = $rw['mdid'];
				$this->data['title'] = $rw['md_title'];
				$this->data['alttext'] = stripslashes($rw['md_alttext']);
				$this->data['text'] = stripslashes($rw['md_text']);
				$this->data['folder'] = $rw['md_folder'];
				$this->data['filename'] = $rw['md_filename'];
				$this->data['dateuploaded'] = $rw['md_dateuploaded'];
				$this->data['type'] = $rw['md_type'];
				$this->data['filesize'] = $rw['md_filesize'];
				$this->data['active'] = $rw['md_active'];
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
        if ($arg == "mdid") { return; }
		
        if (isset($this->data[$arg])) {
            $this->data[$arg] = $val;
        	$val = mres($val);
			try {
				$rs = mq("update " . DB_TBL_MEDIA . " set $arg='$val' where mdid='" . $this->data['id'] . "'");
			} catch(Exception $e) { }
        }
    }
	
	public function updateMedia($post_array,$files_array=NULL,$lvl=NULL) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		$datetime = date("Y-m-d H:i:s");
		
		$mdid = $this->data['id'];
	    if($mdid == "") {
	        $rs = mq("insert into " . DB_TBL_MEDIA . " (md_title,md_alttext,md_text,md_dateuploaded) values ('$md_title','$md_alttext','$md_text','$datetime')");
	        $mdid = miid();
	    } else {
	    	if($files['newmedia']['name'] != "") { $this->deleteMediaFile($lvl); }
	        $rs = mq("update " . DB_TBL_MEDIA . " set md_title='$md_title', md_alttext='$md_alttext', md_text='$md_text' where mdid='$mdid'");	
	    }
		
	    if($files_array['newmedia']['name'] != "") {
	        $y = date("Y",strtotime($datetime));
	        $m = date("m",strtotime($datetime));
	        $target_path = $lvl . "uploads/media/$y/";
	        if(!file_exists($target_path)) mkdir($target_path);
	        
	        $target_path = $lvl . "uploads/media/$y/$m/";
	        if(!file_exists($target_path)) mkdir($target_path);
			$base_folder = "uploads/media/$y/$m/";
			
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
	            $rs = mq("update " . DB_TBL_MEDIA . " set md_filename='$filename', md_type='$mdtype', md_filesize='$mdsize', md_folder='$base_folder' where mdid='$mdid'");
	        }
	    }
	   	
	   	
	   if(sizeof($post_array['cid']) > 0) {
			updateCategoryLink($post_array['cid'],$mdid,'media');
		}
		
		$_SESSION['_mtype'] = "S";
		$_SESSION['_msg'] = "newmedia";
		return $mdid;
	}
	function getCid() {
		if($this->mdid != "") {
			return getCategoryId($this->data['id'],'media');
		}
	}
	public function categories() {
		if($this->data['id'] != "") {
			return getCategoryArray($this->data['id'],'media');
		}
	}
	public function categoryNames() {
		return getCategoryNames($this->data['id'],'media',',');
	}
	public function inCategory($catids) {
		return inCategory($catids,$this->id,'media');
	}

	/**
	 * Returns the location of a media item, or prints it out to screen 
	 *
	 * @param options
	 *   An array of options:
	 * 	 - echo (default false): To echo out img tag with source
	 *   - style: style to include in the style argument
	 *   - class: class to include in the class argument
	 *   - lvl: level of root folder compared to current loation
	 *
	 * @return
	 *   string with image loation, or img tag
	 */
	public function getLocation($options) {
		$location = $this->location();
		
		extract($options);
		if(!$echo) return $location;
		if($style != "") { $x_style = "style=\"$style\""; }
		if($class != "") { $x_class = "class=\"$class\""; }
		$location = $lvl . $location;
		$alt = $this->data['alttext'];
		
		$img = "<img src=\"$location\" $x_style $xclass alt=\"$alt\" />";
		
		return $img;
	}

	public function deleteMedia() {
		if($this->data['id'] != NULL) {
			$rs = mq("update " . DB_TBL_MEDIA . " set md_active='0' where mdid='" . $this->data['id'] . "'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedmedia";
		}
	}
	private function deleteMediaFile($lvl) {
		$file = $this->location();
		unlink($lvl . $file);
	}
	
	private function location() {
		return $this->data['folder'] . $this->data['filename'];
	}
	
}
?>