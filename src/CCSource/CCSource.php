<?php

class CCSource extends CObject implements IController {
	
	public function __construct() {
		parent::__construct();
	}
		
	public function Index() {
		include('source.php');
		$this->data['main'] = $html;
	}
}