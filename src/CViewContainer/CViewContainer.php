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
	
	public function AddInclude($file, $variables=array(), $region='primary') {
		$this->views[$region][] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
		return $this;
	}
	
	public function AddString($str, $vars=array(), $region='primary') {
		$this->views[$region][] = array('type' => 'string', 'string' => $str, 'variables' => $vars);
		return $this;
	}
	
	public function AddStyle($style) {
		if(isset($this->data['inline_style'])) {
			$this->data['inline_style'] .= $style;
		}else {
			$this->data['inline_style'] = $style;
		}
		return $this;
	}
	
	public function RegionHasView($region='default') {
		if(is_array($region)) {
			foreach($region as $val) {
				if(isset($this->views[$val])) {
					return true;
				}
			}
			return false;
		} else {
			return (isset($this->views[$region]));
		}
	}
	
	public function Render($region='default') {
		if(!isset($this->views[$region])) {
			return;
		}
		
		foreach($this->views[$region] as $view) {
			switch($view['type']) {
				case 'include':
					extract($view['variables']);
					include($view['file']);
					break;
				case 'string':
					if(!empty($view['variables'])){ extract($view['variables']); }
					echo  $view['string'];
					break;
			}
		}
	}
}