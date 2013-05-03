<?php

class CCIndex extends CObject implements IController {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function Index() {
		$this->views->SetTitle('jrnek Framwork');
		$modules = new CMModules();
		$controllers = $modules->AvailableControllers();
		$this->views->AddInclude(__DIR__ . '/index.tpl.php');
		$this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array('controllers' => $controllers), 'secondary');
	}
}