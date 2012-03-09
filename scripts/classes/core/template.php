<?php
class Template extends Post {
	
	/* PUBLIC FUNCTIONS */
	public function __construct($slug=NULL) {
		parent::__construct($slug);
	}
	
	public function updateTemplate($post_array) {
		$_SESSION['_mtype'] = "S";
		$_SESSION['_msg'] = "newtemplate";
		return parent::updatePost($post_array,'template');
	}
	public function deleteTemplate($post_array) {
		$_SESSION['_mtype'] = "W";
		$_SESSION['_msg'] = "deletedtemplate";
		parent::deletePost();
	}
}
?>