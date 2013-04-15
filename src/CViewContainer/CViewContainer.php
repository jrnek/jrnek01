<?php

class CViewContainer {
	
	private $data = array();
	private $views = array();
	
	public function __construct() { ; }
	
	public function GetData() {
		return $this->data;
	}
	
	public function SetTitle($value) {
		$this->SetVariable('title', $value);
	}
	
	public function SetVariable($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function AddInclude($file, $variables=array()) {
		$this->views[] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
	}
	
	public function Render() {
		foreach($this->views as $view) {
			switch($view['type']) {
				case 'include':
					extract($view['variables']);
					include($view['file']);
					break;
			}
		}
	}
}