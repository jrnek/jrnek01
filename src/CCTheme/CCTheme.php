<?php

class CCTheme extends CObject implements IController {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function Index() {
		$this->views->SetTitle('Theme');
		$this->views->AddString('This is the primary region', array(), 'primary');
		$this->views->AddString('This is the sidbar region', array(), 'secondary');
		$this->views->AddString('This is flash!', array(), 'flash');
	}
	
	public function AllRegions() {
		$this->views->SetTitle('Theme: All regions');
		foreach($this->config['theme']['regions'] as $val) {
			$this->views->AddString("This region: $val", array(), $val); 
			$this->views->AddStyle('#'.$val.'{background-color: rgba(126, 206, 253, 0.2);}');
		}
	}
	
	public function SomeRegions() {
		$this->views->SetTitle('Theme: Some regions');
		$this->views->AddString('This is the primary region', array(), 'primary');
		$this->views->AddStyle('#primary{background-color: rgba(126, 206, 253, 0.2);}');
		
		if(func_num_args()) {
			foreach(func_get_args() as $val) {
				$this->views->AddString("This is $val", array(), $val);
				$this->views->AddStyle('#'.$val.'{background-color: rgba(126, 206, 253, 0.2);}');
			}
		}
	}
	
	public function TestPage() {
		$this->views->SetTitle('TestPage');
		$this->views->AddInclude(__DIR__ . '/h1h6.tpl.php', array(), 'primary');	
	}
}